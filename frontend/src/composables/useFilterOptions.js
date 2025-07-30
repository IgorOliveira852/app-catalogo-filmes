import { ref } from 'vue'
import axiosClient from '../axios.js'

export function useFilterOptions() {
    const options = ref({})

    async function fetchOptions(slug) {
        try {
            const { data } = await axiosClient.get(`/movies/filters/${slug}`)
            options.value[slug] = data
        } catch (err) {
            console.error(`Erro ao buscar ${slug}:`, err)
            options.value[slug] = []
        }
    }

    async function init(slugs = []) {
        await Promise.all(slugs.map(fetchOptions))
    }

    return {
        options,
        init
    }
}