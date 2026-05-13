import BaseController from "./BaseController";
import LaravelUi5 from "com/laravelui5/core/LaravelUi5";
import JSONModel from "sap/ui/model/json/JSONModel";
import RadioButtonGroup from "sap/m/RadioButtonGroup";

interface ResultState {
	visible: boolean;
	type: "None" | "Information" | "Success" | "Warning" | "Error";
	text: string;
}

interface ActionError extends Error {
	status?: number;
	cause?: {
		message?: string;
		errors?: Record<string, string[]>;
	};
}

interface SerializedIntent {
	kind: string;
	version: string;
	payload: Record<string, unknown>;
}

interface IntentResponse {
	status: "satisfied" | "needs_more_input" | "error";
	next_step: object | null;
	errors: Record<string, string[]> | null;
	next: SerializedIntent | null;
}

const LEGAL_FORM_SOLE_PROPRIETOR = 1;
const LEGAL_FORM_COMPANY = 2;

/**
 * @namespace io.pragmatiqu.auth.controller
 */
export default class OrgSetup extends BaseController {
	public onInit(): void {
		this.setModel(new JSONModel({
			legal_form: LEGAL_FORM_COMPANY,
			org_name: "",
			company_size: "",
			function: "",
			billing_email: "",
			marketing_consent: false,
			submitting: false,
			result: { visible: false, type: "None", text: "" } as ResultState,
		}), "orgSetup");

		const route = this.getRouter().getRoute("OrgSetup");
		route?.attachPatternMatched(() => this.onMatched());

		this.getView().addEventDelegate({
			onAfterShow: () => {
				setTimeout(() => (this.byId("legalFormGroup") as RadioButtonGroup).focus(), 0);
			}
		});
	}

	private onMatched(): void {
		const state = this.getModel("orgSetup") as JSONModel;
		state.setProperty("/result", { visible: false, type: "None", text: "" } as ResultState);
		state.setProperty("/submitting", false);
	}

	public onLegalFormSelect(event: { getSource: () => unknown }): void {
		const group = event.getSource() as RadioButtonGroup;
		const index = group.getSelectedIndex();
		const state = this.getModel("orgSetup") as JSONModel;

		if (index === 0) {
			state.setProperty("/legal_form", LEGAL_FORM_SOLE_PROPRIETOR);
			// Sole-proprietor smart defaults — the org IS the person, the
			// company size is one. Both stay editable.
			const personName = (this.getOwnerComponent().getModel("intent") as JSONModel | undefined)
				?.getProperty("/payload/personName") as string | undefined;
			if (personName && !state.getProperty("/org_name")) {
				state.setProperty("/org_name", personName);
			}
			if (!state.getProperty("/company_size")) {
				state.setProperty("/company_size", "solo");
			}
		}
		if (index === 1) {
			state.setProperty("/legal_form", LEGAL_FORM_COMPANY);
		}
	}

	public async onSubmit(): Promise<void> {
		const state = this.getModel("orgSetup") as JSONModel;
		const bundle = await this.getResourceBundle();

		const payload = {
			legal_form: state.getProperty("/legal_form"),
			org_name: state.getProperty("/org_name"),
			company_size: state.getProperty("/company_size"),
			function: state.getProperty("/function"),
			billing_email: state.getProperty("/billing_email"),
			marketing_consent: state.getProperty("/marketing_consent"),
		};

		state.setProperty("/submitting", true);
		try {
			const response = await LaravelUi5.post<IntentResponse>(
				"/auth/intents/org-setup",
				payload
			);

			if (response.status === "satisfied" && response.next) {
				this.getOwnerComponent().dispatchIntent(response.next);
				return;
			}

			if (response.status === "error" && response.errors) {
				this.setStrip(state, "Error", this.firstError(response.errors));
				return;
			}
		} catch (error) {
			const err = error as ActionError;
			const text = err.cause?.message ?? bundle.getText("orgSetup.failed") ?? "";
			this.setStrip(state, "Error", text);
		} finally {
			state.setProperty("/submitting", false);
		}
	}

	private setStrip(state: JSONModel, type: ResultState["type"], text: string): void {
		state.setProperty("/result", { visible: true, type, text } as ResultState);
	}

	private firstError(errors: Record<string, string[]>): string {
		for (const messages of Object.values(errors)) {
			if (messages.length > 0) return messages[0];
		}
		return "";
	}
}
