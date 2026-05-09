import BaseController from "./BaseController";
import Input from "sap/m/Input";
import MessageBox from "sap/m/MessageBox";
import LaravelUi5 from "com/laravelui5/core/LaravelUi5";
import JSONModel from "sap/ui/model/json/JSONModel";

interface SerializedIntent {
	kind: string;
	version: string;
	payload: Record<string, unknown>;
}

interface LoginResponse {
	message: string;
	next: SerializedIntent;
}

interface ActionError {
	cause: { message: string };
	statusText: string;
}

/**
 * @namespace io.pragmatiqu.auth.controller
 */
export default class Login extends BaseController {
	public onInit(): void {
		this.getView().addEventDelegate({
			onAfterShow: () => {
				setTimeout(() => (this.byId("emailInput") as Input).focus(), 0);
			}
		});
	}

	public async onLogin(): Promise<void> {
		// Read credentials directly from the Input controls rather than the
		// shared `login` JSONModel. The model is a downstream cache, and
		// browser autofill / focus-loss timing can leave it stale even
		// with `valueLiveUpdate="true"` on the inputs (autofill events
		// some browsers swallow, model-vs-DOM races on submit). The
		// control's getValue() is the displayed value — i.e. what the
		// user actually sees in the field — so it's the source of truth
		// for what they intend to submit.
		const login = <JSONModel> this.getModel("login");
		const payload = {
			email: (this.byId("emailInput") as Input).getValue(),
			password: (this.byId("passwordInput") as Input).getValue(),
			keepSignedIn: login.getProperty("/keepSignedIn"),
		};
		try {
			const response = await LaravelUi5.post(
				"/auth/login",
				payload
			) as LoginResponse;
			await this.getOwnerComponent().dispatchIntent(response.next);
		}
		catch (error) {
			const err = error as ActionError;
			MessageBox.error(err.cause.message, {title: err.statusText});
		}
	}
}
