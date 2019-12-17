<template>
  <div class="w-1/2">
        <h2 class="text-2xl">Battle Log</h2>
        <div id="battle-log" class="w-full p-4 bg-gray-800 rounded-lg mt-2 overflow-y-auto" style="height: 528px;">
            <ul class="text-green-400" v-if="logs.length > 0">
                <li class="py-2" v-for="(log, index) in logs" :key="index" v-html="log">{{log}}</li>
            </ul>
            <span class="text-green-400 py-2" v-else>
                Loading...
            </span>
        </div>
        <div class="w-full flex justify-between mt-4">
            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4" :class="logs.length == 1?'cursor-not-allowed':''" :disabled="logs.length == 1" @click="resetGame">
                Reset Battle
            </button>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4" @click="runAttack" :class="isInProgress?'cursor-not-allowed':''" :disabled="isInProgress">
                Run Attack 🚀
            </button>
            <!-- <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 opacity-50 cursor-not-allowed">
                Run Attack 🚀
            </button> -->
        </div>
    </div>
</template>

<script>
export default {
    props:['game_id'],
    data(){
        return {
            logs: [],
            isInProgress: false
        }
    },
    async mounted(){
        await this.getBattleLogs();        
    },
    methods:{
        async getBattleLogs(){
            try {
                const battleLogs = await axios.get('/api/game/'+this.game_id+'/logs');    
                this.logs = battleLogs.data; 
            } catch (error) {
                alert(error)
            }
        },
        async runAttack(){
            try {
                this.isInProgress = true
                const battleLogs = await axios.post('/api/game/'+this.game_id+'/attack');    
                this.logs = [...this.logs, ...battleLogs.data];
                this.isInProgress = false
                window.Event.$emit('fresh-armies');
            } catch (error) {
                alert(error)
            }
        },
        async resetGame(){
            try {
                const battleLogs = await axios.put('/api/game/'+this.game_id+'/reset');    
                this.logs = ["Game not started"];     
                window.Event.$emit('fresh-armies');   
            } catch (error) {
                alert(error)
            }
        },
    },
    updated(){       
        const objDiv = window.document.getElementById("battle-log");
        objDiv.scrollTop = objDiv.scrollHeight;
    },

}
</script>

<style>

</style>