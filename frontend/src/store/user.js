import {defineStore} from "pinia";
import axiosClient from "../axios.js";

const useUserStore = defineStore('user', {
    state: () => ({
        user: null
    }),
    actions: {
        fetchUser() {
            return axiosClient.get('/user')
                .then(({data}) => {
                    console.log(data)
                    this.user = data
                })
        }
    }
})

export default useUserStore;