<template>
    <div class="bg-white rounded-lg w-1/3 self-center shadow-lg p-10">
        <div>
            <h2 class="text-2xl">Games</h2>
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
                        <a href="/game/1">
                            Game {{game.id}}
                        </a>
                    </td>
                    <td>{{game.armies.length}}</td>
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
        }
    }
}
</script>

<style>

</style>