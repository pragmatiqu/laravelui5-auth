import BaseController from "./BaseController";
import Input from "sap/m/Input";
import LaravelUi5 from "com/laravelui5/core/LaravelUi5";
import JSONModel from "sap/ui/model/json/JSONModel";

interface ActionError extends Error {
	status?: number;
	cause?: {
		message?: string;
		errors?: Record<string, string[]>;
	};
}

interface ResultState {
	visible: boolean;
	type: "None" | "Information" | "Success" | "Warning" | "Error";
	text: string;
}

const THROTTLE_PATTERN = /^auth_throttle\|(\d+)\|(\d+)$/;

/**
 * @namespace io.pragmatiqu.auth.controller
 */
export default class ForgotPassword extends BaseController {
	public onInit(): void {
		this.setModel(new JSONModel({
			submitting: false,
			result: { visible: false, type: "None", text: "" } as ResultState
		}), "forgotPassword");

		this.getView().addEventDelegate({
			onAfterShow: () => {
				setTimeout(() => (this.byId("emailInput") as Input).focus(), 0);
			}
		});
	}

	public async onContinue(): Promise<void> {
		const login = <JSONModel> this.getModel("login");
		const state = <JSONModel> this.getModel("forgotPassword");
		const bundle = await this.getResourceBundle();

		state.setProperty("/submitting", true);
		try {
			await LaravelUi5.post(
				"/auth/forgot-password",
				{ email: login.getProperty("/email") }
			);
			this.setStrip(state, "Success", bundle.getText("forgotPassword.success") ?? "");
		} catch (error) {
			const seconds = this.parseThrottleSeconds(error as ActionError);
			if (seconds !== null) {
				const text = bundle.getText("forgotPassword.throttle", [seconds]) ?? "";
				this.setStrip(state, "Error", text);
				return;
			}
			// Fold validation / network / server errors into the success strip
			// to preserve email-enumeration uniformity.
			this.setStrip(state, "Success", bundle.getText("forgotPassword.success") ?? "");
		} finally {
			state.setProperty("/submitting", false);
		}
	}

	private setStrip(state: JSONModel, type: ResultState["type"], text: string): void {
		state.setProperty("/result", { visible: true, type, text } as ResultState);
	}

	private parseThrottleSeconds(error: ActionError): number | null {
		const messages = error.cause?.errors?.email;
		if (!messages || messages.length === 0) {
			return null;
		}
		const match = THROTTLE_PATTERN.exec(messages[0]);
		return match ? parseInt(match[1], 10) : null;
	}
}
