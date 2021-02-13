import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    deleteModalObj: {
      showDeleteModal: false,
      deleteUrl: "app/delete_tag",
      data: null,
      deletingIndex: -1,
      isDeleted: false,
    },
  },
  getters: {
    getDeleteModalObj(state) {
      return state.deleteModalObj
    }
  },
  mutations: {
    setDeleteModal(state, payload) {
      const deleteModalObj = {
        showDeleteModal: false,
        deleteUrl: "",
        data: null,
        deletingIndex: -1,
        isDeleted: payload,
      };

      state.deleteModalObj = deleteModalObj
    },
    setDeletingModalObj(state, payload) {
      state.deleteModalObj = payload
    }
  },
  actions: {

  }
})

