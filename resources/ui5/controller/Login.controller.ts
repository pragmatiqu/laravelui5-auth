import BaseController from "./BaseController";
import UIComponent from "sap/ui/core/UIComponent";
import JSONModel from "sap/ui/model/json/JSONModel";
import MessageBox from "sap/m/MessageBox";

/**
 * @namespace io.pragmatiqu.auth.controller
 */
export default class Login extends BaseController {
	public onInit(): void {
		const component = <UIComponent> this.getOwnerComponent();
		const meta = component.getManifestEntry("/laravel.ui5/meta");
		const model = new JSONModel({email: null, password: null, meta: meta});
		this.setModel(model, "login");
	}

	public async onLogin(): Promise<void> {
		MessageBox.error("So isses!");
	}
}
