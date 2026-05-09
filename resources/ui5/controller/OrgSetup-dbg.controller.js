sap.ui.define(["./BaseController", "com/laravelui5/core/LaravelUi5", "sap/ui/model/json/JSONModel"], function (__BaseController, __LaravelUi5, JSONModel) {
  "use strict";

  function _interopRequireDefault(obj) {
    return obj && obj.__esModule && typeof obj.default !== "undefined" ? obj.default : obj;
  }
  const BaseController = _interopRequireDefault(__BaseController);
  const LaravelUi5 = _interopRequireDefault(__LaravelUi5);
  const LEGAL_FORM_SOLE_PROPRIETOR = 1;
  const LEGAL_FORM_COMPANY = 2;

  /**
   * @namespace io.pragmatiqu.auth.controller
   */
  const OrgSetup = BaseController.extend("io.pragmatiqu.auth.controller.OrgSetup", {
    onInit: function _onInit() {
      this.setModel(new JSONModel({
        legal_form: LEGAL_FORM_COMPANY,
        org_name: "",
        company_size: "",
        function: "",
        marketing_consent: false,
        submitting: false,
        result: {
          visible: false,
          type: "None",
          text: ""
        }
      }), "orgSetup");
      const route = this.getRouter().getRoute("OrgSetup");
      route?.attachPatternMatched(() => this.onMatched());
      this.getView().addEventDelegate({
        onAfterShow: () => {
          setTimeout(() => this.byId("legalFormGroup").focus(), 0);
        }
      });
    },
    onMatched: function _onMatched() {
      const state = this.getModel("orgSetup");
      state.setProperty("/result", {
        visible: false,
        type: "None",
        text: ""
      });
      state.setProperty("/submitting", false);
    },
    onLegalFormSelect: function _onLegalFormSelect(event) {
      const group = event.getSource();
      const index = group.getSelectedIndex();
      const state = this.getModel("orgSetup");
      if (index === 0) {
        state.setProperty("/legal_form", LEGAL_FORM_SOLE_PROPRIETOR);
        // Sole-proprietor smart defaults — the org IS the person, the
        // company size is one. Both stay editable.
        const personName = this.getOwnerComponent().getModel("intent")?.getProperty("/payload/personName");
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
    },
    onSubmit: async function _onSubmit() {
      const state = this.getModel("orgSetup");
      const bundle = await this.getResourceBundle();
      const payload = {
        legal_form: state.getProperty("/legal_form"),
        org_name: state.getProperty("/org_name"),
        company_size: state.getProperty("/company_size"),
        function: state.getProperty("/function"),
        marketing_consent: state.getProperty("/marketing_consent")
      };
      state.setProperty("/submitting", true);
      try {
        const response = await LaravelUi5.post("/auth/intents/org-setup", payload);
        if (response.status === "satisfied" && response.next) {
          this.getOwnerComponent().dispatchIntent(response.next);
          return;
        }
        if (response.status === "error" && response.errors) {
          this.setStrip(state, "Error", this.firstError(response.errors));
          return;
        }
      } catch (error) {
        const err = error;
        const text = err.cause?.message ?? bundle.getText("orgSetup.failed") ?? "";
        this.setStrip(state, "Error", text);
      } finally {
        state.setProperty("/submitting", false);
      }
    },
    setStrip: function _setStrip(state, type, text) {
      state.setProperty("/result", {
        visible: true,
        type,
        text
      });
    },
    firstError: function _firstError(errors) {
      for (const messages of Object.values(errors)) {
        if (messages.length > 0) return messages[0];
      }
      return "";
    }
  });
  return OrgSetup;
});
//# sourceMappingURL=OrgSetup-dbg.controller.js.map
