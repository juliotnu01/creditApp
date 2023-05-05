import { defineStore } from 'pinia'

export const useComponentStore = defineStore('ComponentStore', {
    state: () => ({
        sideBar:false
    }),
    getters: {
        getterSideBar: (state) => state.sideBar
    },
    actions: {
        setSideBar(state, data){
            this.sideBar = data
        }
    }
})