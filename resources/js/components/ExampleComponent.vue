<template>
    <v-app>
        <v-container fluid>
            <form @submit="submitForm">
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <v-text-field
                        required type="text" id="full_name" v-model="full_name"
                    ></v-text-field>
                </div>
                <div class="form-group ">
                    <label for="country">Country</label>
                    <v-autocomplete
                        v-model="country"
                        :items="countries"

                    >
<!--                        <template v-slot:selection="{ item, index }">
                            {{ item.name }}
                        </template>
                        <template v-slot:item="{ item }">
                            {{ item.name }}
                        </template>-->
                    </v-autocomplete>

                </div>
                <div class="form-group mt-2">
                    <label for="country">Phone</label>
                    <div class="d-flex align-items-center">
                        <span>{{phone_index}}</span>
                        <v-text-field
                            type="number" required v-model="phone" id="phone" class="ms-2"
                        ></v-text-field>
                    </div>

                </div>

                <div class="form-group mt-2">
                    <label for="email">Email</label>
                    <div class="d-flex align-items-center">
                        <v-text-field
                            :messages="this.errors.email"
                            required v-model="email" type="email" id="email"
                        ></v-text-field>
                    </div>

                </div>

                <button class="btn btn-primary mt-4" type="submit">Submit</button>
            </form>


        </v-container>
    </v-app>
</template>

<script>
import Vuetify from 'vuetify'
export default {
    vuetify: new Vuetify(),
    data () {
        return {
            full_name: '',
            countries:[],
            phone: null,
            country: null,
            phone_index:null,
            email:null,
            errors:[],
        }
    },
    watch: {
        country (value) {
            console.log(value,this.countries[value] ,'country changed')
            if(this.countries[value]){
                this.phone_index = this.countries[value].phone_index
            }

        }
    },
    methods: {
        submitForm(e){
            e.preventDefault();
            const data = {
                country: this.country,
                phone: this.phone,
                email: this.email,
                name: this.full_name,
                phone_index:this.phone_index
            }
            axios.post('/register',data).then(response => {
                if(response.data.success === 'error'){
                    this.errors = response.data.errors
                    console.log(this.errors, 'this.errors')
                }else{
                    console.log(response.data, 'exaaaaaaav')
                }
            }).catch(error => {

            })
        }
    },
    mounted() {

        axios.get('/api/countries').then(response => {
            let conts = [];
            response.data.countries.forEach(val => {
                conts[val.id] = {
                    text:val.name,
                    value: val.id,
                    phone_index: val.phone_index
                }
            })
            this.countries = conts;
            this.country = this.countries[0]
            console.log(response);
        })
        console.log('Component mounted.')
    }
}
</script>

<style>
    .flag{
        width: 60px;
        object-fit: cover;
        height: 40px;
    }
</style>
