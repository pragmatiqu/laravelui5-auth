import BaseController from "./BaseController";
import LaravelUi5 from "com/laravelui5/core/LaravelUi5";
import JSONModel from "sap/ui/model/json/JSONModel";
import {Route$PatternMatchedEvent} from "sap/ui/core/routing/Route";

interface ResetPasswordResponse {
	message: string;
	redirect: string;
}

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

/**
 * @namespace io.pragmatiqu.auth.controller
 */
export default class SetPassword extends BaseController {
	public onInit(): void {
		this.setModel(new JSONModel({
			submitting: false,
			done: false,
			result: { visible: false, type: "None", text: "" } as ResultState
		}), "setPassword");

		const route = this.getRouter().getRoute("SetPassword");
		route?.attachPatternMatched((event: Route$PatternMatchedEvent) => this.onRouteMatched(event));
	}

	private onRouteMatched(event: Route$PatternMatchedEvent): void {
		const args = event.getParameter("arguments") as { token?: string; email?: string };
		const login = this.getModel("login") as JSONModel;

		login.setProperty("/token", args.token ?? "");
		login.setProperty("/email", args.email ?? "");
		login.setProperty("/password", "");
		login.setProperty("/passwordConfirmation", "");

		const state = this.getModel("setPassword") as JSONModel;
		state.setProperty("/done", false);
		state.setProperty("/result", { visible: false, type: "None", text: "" } as ResultState);
	}

	public async onContinue(): Promise<void> {
		const login = this.getModel("login") as JSONModel;
		const state = this.getModel("setPassword") as JSONModel;
		const bundle = await this.getResourceBundle();

		const payload = {
			token: login.getProperty("/token") as string,
			email: login.getProperty("/email") as string,
			password: login.getProperty("/password") as string,
			password_confirmation: login.getProperty("/passwordConfirmation") as string,
		};

		state.setProperty("/submitting", true);
		try {
			await LaravelUi5.post(
				"/auth/reset-password",
				payload
			) as ResetPasswordResponse;
			// Drop sensitive fields the moment the reset succeeds. The shared
			// `login` model is consumed by the Login view too — leaving the
			// new password in there would pre-fill the sign-in form with it.
			login.setProperty("/password", "");
			login.setProperty("/passwordConfirmation", "");
			login.setProperty("/token", "");
			state.setProperty("/done", true);
			this.setStrip(state, "Success", bundle.getText("setPassword.success") ?? "");
		} catch (error) {
			const err = error as ActionError;
			const text = this.diagnoseFailure(err)
				? bundle.getText("setPassword.passwordError") ?? ""
				: bundle.getText("setPassword.failed") ?? "";
			this.setStrip(state, "Error", text);
		} finally {
			state.setProperty("/submitting", false);
		}
	}

	private setStrip(state: JSONModel, type: ResultState["type"], text: string): void {
		state.setProperty("/result", { visible: true, type, text } as ResultState);
	}

	/**
	 * Returns true if the failure is a password validation error (user can fix
	 * by adjusting their input), false if it's a token/email/expired failure
	 * (user must request a new reset link).
	 */
	private diagnoseFailure(error: ActionError): boolean {
		const errors = error.cause?.errors;
		return Boolean(errors?.password && errors.password.length > 0);
	}
}
