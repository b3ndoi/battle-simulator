<template>
    <div class="w-2/3 px-3 mt-2">
        <div class="mb-2 bg-white shadow rounded-lg p-2 flex justify-between items-center w-full" v-for="army in armies" :key="army.id">
            <div>
                <span class="font-semibold">{{army.name}}</span><br>
                <span class="font-semibold">Units: {{(army.units <= 0 )?0:army.units}}</span><br>
                <span class="font-semibold">Strategy: {{army.strategy}}</span>
            </div>
            <span class="text-white py-2 px-2 rounded-full" :class="(army.units <= 0 )?'bg-red-600':'bg-blue-600'"></span>
        </div>
    </div>
</template>

<script>
export default {
    props:['game_id'],
    data(){
        return {
            armies: []
        }
    },
    async mounted(){
        this.getArmies();
        window.Event.$on('fresh-armies',async () => await this.getArmies())
    },
    methods:{
        async getArmies(){
            try {
                const armies = await axios.get(`/api/game/${this.game_id}/armies`);
                this.armies = armies.data;
            } catch (error) {
                alert(error);
            }
        }
    }
}
</script>