import * as types from './mutation-types';

export const actions = {
    changeTemplate({commit, state}, template) {
        commit(types.CHANGE_TEMPLATE, template);
    }
}
