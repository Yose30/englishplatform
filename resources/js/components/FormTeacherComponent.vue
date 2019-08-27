<template>
    <div>
        <b-form @submit.prevent="onSubmit" @reset.prevent="onReset">
            <b-form-group label="Nombre">
                <b-form-input v-model="form.name" :disabled="processing" required></b-form-input>
                <div id="error" v-if="errors && errors.name">{{ errors.name[0] }}</div>
            </b-form-group>
            <b-form-group label="Apeliidos">
                <b-form-input v-model="form.last_name" :disabled="processing" required></b-form-input>
                <div id="error" v-if="errors && errors.last_name">{{ errors.last_name[0] }}</div>
            </b-form-group>
            <b-form-group label="Correo electronico">
                <b-form-input v-model="form.email" :disabled="processing" type="email" required></b-form-input>
                <div id="error" v-if="errors && errors.email">{{ errors.email[0] }}</div>
            </b-form-group>
            <b-form-group label="Telefono">
                <b-form-input v-model="form.telephone" :disabled="processing" required></b-form-input>
                <div id="error" v-if="errors && errors.telephone">{{ errors.telephone[0] }}</div>
            </b-form-group>
            <b-button type="submit" variant="primary" :disabled="processing">
                <b-spinner small v-if="processing"></b-spinner> {{ !processing ? 'Guardar' : 'Guardando' }}
            </b-button>
            <b-button type="reset" variant="danger" :disabled="processing">Borrar datos</b-button>
        </b-form>
    </div>
</template>

<script>
    export default {
        props: ['teacher'],
        name: 'app',
        data() {
            return {
                processing: false,
                form: {
                    id: this.teacher.id,
                    name: this.teacher.name,
                    last_name: this.teacher.last_name,
                    email: this.teacher.email,
                    telephone: this.teacher.telephone
                },
                errors: {}
            }
        },
        methods: {
            onSubmit(){
                this.processing = true;
                if(this.teacher.id == 0){
                    axios.post('/administrator/save_teacher', this.form).then(response => {
                        this.errors = {};
                        this.processing = false;
                        this.$emit('actListaTeachers', response.data);
                    })
                    .catch(error => {
                        this.showErrors(error);
                    });
                }
                else{
                    axios.put('/administrator/update_teacher', this.form).then(response => {
                        this.errors = {};
                        this.processing = false;
                        this.$emit('actRegisterTeacher', response.data);
                    })
                    .catch(error => {
                        this.showErrors(error);
                    });
                }
            },
            onReset(){
                this.form = {
                    id: 0,
                    name: '',
                    last_name: '',
                    email: '',
                    telephone: '',
                    username: ''
                }
            },
            showErrors(error){
                this.processing = false;
                this.errors = {};
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
                else{
                    this.toast('b-toaster-top-left', 'danger', 'Ocurrio un problema, vuelve a intentar');
                }
            },
            toast(toaster, variant, content, append = true) {
                this.$bvToast.toast(content, {
                    title: 'Mensaje',
                    toaster: toaster,
                    variant: variant,
                    solid: true,
                    appendToast: append
                })
            }
        }
    }
</script>

<style>
    #error {
        color: red;
        font-size: 13px;
    }
</style>