import store from "@/Store/store";


export default class LocalStorageUtils {

    static saveState() {
        const state = store.getState();
        LocalStorageUtils.saveAuthReducerState(state.authReducer);
        LocalStorageUtils.saveSettingsReducerState(state.settingsReducer);
    }

    static getData(key = 'auth') {
        return localStorage.getItem(key)
            ? JSON.parse(localStorage.getItem(key)) : {};
    }

    static saveAuthReducerState(state) {
        localStorage.setItem('auth', JSON.stringify(state));
    }

    static saveSettingsReducerState(state) {
        localStorage.setItem('settings', JSON.stringify(state));
    }


}
