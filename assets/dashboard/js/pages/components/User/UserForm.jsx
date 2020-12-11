import React, { Component } from 'react';

import axios             from "axios";
import toastr            from "toastr";

import { Input, Checkbox }     from "@dashboardComponents/Tools/Fields";
import { Alert }               from "@dashboardComponents/Tools/Alert";

import Validateur              from "@dashboardComponents/functions/validateur";

export class UserForm extends Component {
    constructor(props) {
        super(props);

        this.state = {
            username: props.username,
            email: props.email,
            roles: [],
            password: '',
            passwordConfirm: '',
            errors: [],
            success: false
        }

        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    componentDidMount() { document.getElementById("username").focus() }

    handleChange = (e) => {
        let name = e.currentTarget.name;
        let value = e.currentTarget.value;

        const {roles} = this.state
        if(name === "roles"){
            value = (e.currentTarget.checked) ? [...roles, ...[value]] :  roles.filter(v => v !== value)
        }

        this.setState({[name]: value})
    }

    handleSubmit = (e) => {
        e.preventDefault();

        const { type, url, messageSuccess } = this.props;
        const { username, password, passwordConfirm, email, roles } = this.state;

        this.setState({ success: false})

        let paramsToValidate = [
            {type: "text", id: 'username', value: username},
            {type: "email", id: 'email', value: email},
            {type: "array", id: 'roles', value: roles}
        ];
        if(type === "create"){
            paramsToValidate = [...paramsToValidate,
                ...[{type: "password", id: 'password', value: password, idCheck: 'passwordConfirm', valueCheck: passwordConfirm}]
            ];
        }

        // validate global
        let validate = Validateur.validateur(paramsToValidate)

        // check validate success
        if(!validate.code){
            this.setState({ errors: validate.errors });
        }else{
            let self = this;
            axios({ method: 'post', url: url, data: self.state })
                .then(function (response) {
                    let data = response.data;
                    self.props.onUpdateList(data)
                    self.setState({ success: messageSuccess, errors: [] })
                    if(type === "create"){
                        self.setState( {
                            username: '',
                            email: '',
                            roles: [],
                            password: '',
                            passwordConfirm: '',
                        })
                        document.getElementById("form").reset();
                    }
                })
                .catch(function (error) {
                    self.setState({ errors: error.response.data })
                    toastr.error("Veuillez vérifier les informations transmises.")
                })
            ;
        }
    }

    render () {
        const { errors, success, username, email, password, passwordConfirm, roles } = this.state;

        let rolesItems = [
            { 'id': 1, 'value': 'ROLE_SUPER_ADMIN',
                'label':        'Super admin',
                'identifiant':  'superamdin'
            },
            { 'id': 2, 'value': 'ROLE_ADMIN',
                'label':        'Admin',
                'identifiant':  'admin'
            },
            { 'id': 0, 'value': 'ROLE_USER',
                'label':        'Utilisateur',
                'identifiant':  'utilisateur'
            },
        ]

        return <>
            <form onSubmit={this.handleSubmit} id="form">
                {success !== false && <Alert type="info">{success}</Alert>}
                <div className="line line-2">
                    <Input valeur={username} identifiant="username" errors={errors} onChange={this.handleChange} >Nom utilisateur</Input>
                    <Input valeur={email} identifiant="email" errors={errors} onChange={this.handleChange} type="email" >Adresse e-mail</Input>
                </div>
                <div className="line">
                    <Checkbox items={rolesItems} name="roles" valeur={roles} errors={errors} onChange={this.handleChange}>Roles</Checkbox>
                </div>
                <div className="line line-2">
                    <Input type="password" valeur={password} identifiant="password" errors={errors} onChange={this.handleChange} >Mot de passe</Input>
                    <Input type="password" valeur={passwordConfirm} identifiant="passwordConfirm" errors={errors} onChange={this.handleChange} >Confirmer le mot de passe</Input>
                </div>
                <div className="line">
                    <div className="form-button">
                        <button type="submit" className="btn btn-primary">Valider la saisie</button>
                    </div>
                </div>
            </form>
        </>
    }
}