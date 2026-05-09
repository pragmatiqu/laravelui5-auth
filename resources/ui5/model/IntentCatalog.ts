/**
 * Frontend catalog of post-login intents.
 *
 * The closed catalog is owned by the auth module: every kind the backend
 * can dispense has an entry here. Unknown kinds returned by the dispenser
 * are dev-time errors (see Component.dispatchIntent).
 *
 * Keep in sync with LaravelUi5\Auth\Intents\IntentKind on the backend.
 */
export interface IntentBinding {
	/** Router target name to navigate to (interactive intents only). */
	view?: string;
	/** Backend URL the submit POSTs to (interactive intents only). */
	endpoint?: string;
	/** True for terminal intents (e.g. redirect) — dispatcher navigates instead of rendering. */
	terminal?: boolean;
}

export const INTENT_CATALOG: Record<string, IntentBinding> = {
	org_setup: {
		view: "OrgSetup",
		endpoint: "/auth/intents/org-setup",
	},
	redirect: {
		terminal: true,
	},
};
