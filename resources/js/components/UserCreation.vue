<template>
    <div class="form-container">
        <div v-if="!registeredUser">
            <h1 class="mb-5">
                Enable 2FA
            </h1>
            <form @submit.prevent="checkForm">
                <div class="form-group">
                    <div class="alert alert-danger" role="alert" v-if="userMessage && userError">
                        {{ userMessage }}
                    </div>
                    <input type="text" class="form-control" placeholder="Full name" v-model="name" name="name">
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" placeholder="Email" v-model="email" name="email">
                </div>
                <div class="form-group">
                    <div class="container">
                        <div class="row">
                            <div class="col-4 pl-0 pr-2">
                                <input type="text" class="d-inline form-control" placeholder="Code" v-model="code"
                                       name="code">
                            </div>
                            <div class="col-8 p-0">
                                <input type="text" class="d-inline form-control" placeholder="Phone" v-model="phone"
                                       name="phone">
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary" type="submit" :disabled="loading">
                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true" v-if="loading"></span>
                    Enviar
                </button>
            </form>
        </div>
        <div v-if="registeredUser">
            <div class="alert alert-success" role="alert" v-if="userMessage && !userError">
                {{ userMessage }}
            </div>
            <div class="text-left mb-5">
                <ol>
                    <li>Download the Authy Application <a href="https://authy.com/download/">here</a></li>
                    <li>Click on 'zigocapital' to show the generated token</li>
                    <li>Enter the token</li>
                </ol>
            </div>
            <h1 class="mb-5">Provide the generated Token</h1>
            <form @submit.prevent="submitToken">
                <div class="form-group">
                    <div class="alert alert-success" role="alert" v-if="tokenMessage && !tokenError">
                        {{ tokenMessage }}
                    </div>
                    <div class="alert alert-danger" role="alert" v-if="tokenMessage && tokenError">
                        {{ tokenMessage }}
                    </div>
                    <input type="text" class="form-control" placeholder="Token" v-model="token" name="token">
                </div>
                <button class="btn btn-primary" type="submit" :disabled="loading">
                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true" v-if="loading"></span>
                    Validate
                </button>
            </form>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                loading: false,
                name: null,
                email: null,
                code: null,
                phone: null,
                userMessage: null,
                userError: null,
                registeredUser: false,
                tokenMessage: null,
                tokenError: null,
                token: null,
            }
        },
        methods: {
            checkForm: function () {
                this.loading = true;

                if (!this.name) {
                    this.userError = true;

                    this.userMessage = 'The name is required';

                    this.loading = false;

                    return;
                }

                if (!this.email) {
                    this.userError = true;

                    this.userMessage = 'The email is required';

                    this.loading = false;

                    return;
                }

                if (!this.code) {
                    this.userError = true;

                    this.userMessage = 'The code is required';

                    this.loading = false;

                    return;
                }

                if (!this.phone) {
                    this.userError = true;

                    this.userMessage = 'The phone is required';

                    this.loading = false;

                    return;
                }

                let dataForm = new FormData();
                dataForm.append('name', this.name);
                dataForm.append('code', this.code);
                dataForm.append('phone', this.phone);
                dataForm.append('email', this.email);

                axios.post('/', dataForm).then(response => {
                    this.loading = false;

                    this.registeredUser = true;

                    this.userMessage = 'The user has been successfully registered.'

                    setTimeout(() => {
                        this.userMessage = null;

                        this.userError = false;
                    }, 3000)
                }).catch((error) => {
                    this.message = 'An error has ocurred. Try later :(';

                    this.loading = false;
                });
            },
            submitToken: function () {
                console.log('que pedo');
                let dataForm = new FormData();
                dataForm.append('token', this.token);
                dataForm.append('email', this.email);

                axios.post('token/validate', dataForm).then(response => {
                    this.tokenMessage = 'Valid token :D';

                    setTimeout(() => {
                        this.tokenError = null;

                        this.tokenMessage = null;
                    }, 3000)
                }).catch((error) => {
                    const responseError = error.response.data.errors;

                    this.tokenError = true;

                    this.tokenMessage = responseError['error'][0];

                    setTimeout(() => {
                        this.tokenError = null;

                        this.tokenMessage = null;
                    }, 3000)
                });
            },
        }
    }
</script>
