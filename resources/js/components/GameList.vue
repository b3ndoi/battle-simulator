<template>
    <div class="bg-white rounded-lg w-1/3 self-center shadow-lg p-10">
        <div class="flex justify-between">
            <h2 class="text-2xl">Games</h2>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4" @click="addGame" >
                Add Game
            </button>
        </div>
        <table class="table-auto w-full text-center" v-if="games.length > 0 && !isLoading">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Number of Armies</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="game in games" :key="game.id">
                    <td>
                        <a :href="`/game/${game.id}`">
                            Game {{game.id}}
                        </a>
                    </td>
                    <td>{{game.armies?game.armies.length:0}}</td>
                    <td class="font-semibold" :class="game.status?' text-blue-600':' text-green-600'">{{game.status?'Finished':'Active'}}</td>
                </tr>
            </tbody>
        </table>
        <div v-else>
            {{isLoading?'Loading...':'No games added'}}
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            games:[],
            isLoading: true,
        }
    },
    async mounted(){
        await this.getGames();
    },
    methods:{
        async getGames(){
            try {
                const games = await axios.get('/api/game');
                this.games = games.data
                this.isLoading = false
            } catch (error) {
                alert(error)
            }
        },
        async addGame(){
            try {
                const game = await axios.post('/api/game')
                this.games = [...this.games, game.data]
            } catch (error) {
                alert(error.response.data.message) 
            }
        }
    }
}
</script>

<style>

</style>