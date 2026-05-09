import UIComponent from "sap/ui/core/UIComponent";
import models from "./model/models";
import Device from "sap/ui/Device";
import LaravelUi5 from "com/laravelui5/core/LaravelUi5";
import JSONModel from "sap/ui/model/json/JSONModel";
import {URLHelper} from "sap/m/library";
import {INTENT_CATALOG} from "./model/IntentCatalog";

interface SerializedIntent {
	kind: string;
	version: string;
	payload: Record<string, unknown>;
}

/**
 * @namespace io.pragmatiqu.auth
 */
export default class Component extends UIComponent {
	public static metadata = {
		manifest: "json",
		interfaces: ["sap.ui.core.IAsyncContentCreation"]
	};

	private contentDensityClass: string;

	public init(): void {
		// call the base component's init function
		super.init();

		// create the device model
		this.setModel(models.createDeviceModel(), "device");
		this.setModel(new JSONModel({
			email: null,
			password: null,
			passwordConfirmation: null,
			token: null,
			keepSignedIn: false,
		}), "login");
		this.setModel(new JSONModel({}), "intent");
		LaravelUi5.init(this).then(() => {
			this.getRouter().initialize();
		}).catch((error: unknown) => {
			console.error(error);
		})
	}

	/**
	 * Dispatches a serialized intent returned by the backend dispenser.
	 *
	 * Terminal intents (redirect) navigate the browser. Interactive intents
	 * publish their payload to the "intent" model and route to their view.
	 * Unknown kinds throw — the catalog is closed; an unknown kind here
	 * means the backend version is ahead of the frontend version, which
	 * is a versioning bug to land at dev/CI time, not in production.
	 */
	public dispatchIntent(intent: SerializedIntent): void {
		const binding = INTENT_CATALOG[intent.kind];
		if (!binding) {
			console.error(`[ui5-auth] unknown intent kind: ${intent.kind}`);
			throw new Error(`unknown intent kind: ${intent.kind}`);
		}

		if (binding.terminal && intent.kind === "redirect") {
			URLHelper.redirect((intent.payload as { target: string }).target, false);
			return;
		}

		(this.getModel("intent") as JSONModel).setData(intent);
		this.getRouter().navTo(binding.view);
	}

	/**
	 * This method can be called to determine whether the sapUiSizeCompact or sapUiSizeCozy
	 * design mode class should be set, which influences the size appearance of some controls.
	 * @public
	 * @returns css class, either 'sapUiSizeCompact' or 'sapUiSizeCozy' - or an empty string if no css class should be set
	 */
	public getContentDensityClass(): string {
		if (this.contentDensityClass === undefined) {
			// check whether FLP has already set the content density class; do nothing in this case
			if (document.body.classList.contains("sapUiSizeCozy") || document.body.classList.contains("sapUiSizeCompact")) {
				this.contentDensityClass = "";
			} else if (!Device.support.touch) {
				// apply "compact" mode if touch is not supported
				this.contentDensityClass = "sapUiSizeCompact";
			} else {
				// "cozy" in case of touch support; default for most sap.m controls, but needed for desktop-first controls like sap.ui.table.Table
				this.contentDensityClass = "sapUiSizeCozy";
			}
		}
		return this.contentDensityClass;
	}
}
