
const updateSettingsAction = (state, action) => {

    return {
        ...state,
        ...action.payload
    }
}

export default updateSettingsAction;
