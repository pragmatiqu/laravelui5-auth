import BaseController from "./BaseController";
import MessageBox from "sap/m/MessageBox";
import LaravelUi5 from "com/laravelui5/core/LaravelUi5";
import JSONModel from "sap/ui/model/json/JSONModel";
import {URLHelper} from "sap/m/library";

/**
 * @namespace io.pragmatiqu.auth.controller
 */
export default class Login extends BaseController {
	public onInit(): void {
		this.setModel(new JSONModel({email: null, password: null, keepSignedIn: false}), "login");
	}

	public async onLogin(): Promise<void> {
		const login = <JSONModel> this.getModel("login");
		const payload = {
			email: login.getProperty("/email"),
			password: login.getProperty("/password"),
			keepSignedIn: login.getProperty("/keepSignedIn"),
		}
		try {
			const response = await LaravelUi5.call("io.pragmatiqu.auth.actions.login", {}, payload);
			URLHelper.redirect(response.redirect, false);
		}
		catch (error: any) {
			MessageBox.error(error.cause.message, {title: error.statusText});
		}
	}
}
