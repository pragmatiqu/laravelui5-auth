sap.ui.define([
    "com/laravelui5/core/BaseController",
    "sap/ui/model/json/JSONModel",
    "sap/m/MessageToast",
    "sap/m/MessageBox",
    "com/laravelui5/core/LaravelUi5",
    'sap/m/library'
], function (BaseController, JSONModel, MessageToast, MessageBox, LaravelUi5, library) {
    "use strict";

    const URLHelper = library.URLHelper;

    return BaseController.extend("io.pragmatiqu.auth.controller.Login", {

        onInit: function () {
            const component = this.getOwnerComponent();
            const meta = component.getManifestEntry("/laravel.ui5/meta");
            const model = new JSONModel({email: null, password: null, meta: meta});
            this.getView().setModel(model, "login")
        },

        onContinue: async function(e) {
            const payload = this.getView().getModel("login").getData();
            try {
                const data = await LaravelUi5.call("login", {}, payload);
                MessageToast.show(data.message + " " + data.redirect);

                URLHelper.redirect(data.redirect, false);
            }
            catch (e) {
                MessageBox.error(e.message);
            }
        },

        onNavigate: function (e) {
            const pattern = e.getSource().data("pattern");
            const component = this.getOwnerComponent();
            component.getRouter().navTo(pattern);
        }
    });
});
