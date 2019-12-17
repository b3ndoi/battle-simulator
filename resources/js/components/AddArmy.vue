<template>
  <form @submit.prevent="addArmy()" class="w-1/3">
    <div class="w-full mb-6 md:mb-0">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">
            Name
        </label>
        <input v-model="army.name" autocomplete="off" @keydown="delete errors.name" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="name" type="text" placeholder="Enter Army Name">
        <p v-if="errors.name" class="text-red-500 text-xs italic">{{errors.name[0]}}</p>
    </div>
    <div class="w-full mb-6 md:mb-0">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="units">
            Units
        </label>
        <input v-model="army.units" autocomplete="off" @keydown="delete errors.units" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="units" type="text" placeholder="Units (80 - 100)">
        <p v-if="errors.units" class="text-red-500 text-xs italic">{{errors.units[0]}}</p>
    </div>
    <div class="w-full mb-6 md:mb-0">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="strategy">
            Strategy
        </label>
        <div class="relative">
            <select v-model="army.strategy" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="strategy">
                <option value="random">random</option>
                <option value="weakest">weakest</option>
                <option value="strongest">strongest</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
            </div>
        </div>
    </div>
    <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 w-full mt-4" type="sumbit">
        Add Army
    </button>
</form>
</template>

<script>
export default {
    props:['game_id'],
    data(){
        return {
            army:{
                name:'',
                units:'',
                strategy:'random'
            },
            errors:{},

        }
    },
    methods:{
        async addArmy(){
            try {
                await axios.post(`/api/game/${this.game_id}/armies`, this.army);
                this.army = {
                    name:'',
                    units:'',
                    strategy:'random'
                };
                this.errors = {}
                window.Event.$emit('fresh-armies')
            } catch (error) {
                console.log(error.response.data)
                this.errors = error.response.data.errors
                // alert(error);
            }
        }
    }
}
</script>

<style>

</style>