import React, { Component } from 'react';

import { Button }              from "@dashboardComponents/Tools/Button";
import { Input, Checkbox }     from "@dashboardComponents/Tools/Fields";

export class UserForm extends Component {
    constructor(props) {
        super(props);

        this.state = {
            username:           {value: props.username, error: ''},
            email:              {value: props.email,    error: ''},
            password:           {value: '', error: ''},
            passwordConfirm:    {value: '', error: ''},
            roles:              {value: [], error: ''}
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
            value = (e.currentTarget.checked) ? [...roles.value, ...[value]] :  roles.value.filter(v => v !== value)
        }

        this.setState({[name]: {value: value}})
    }

    handleSubmit = (e) => {
        e.preventDefault();

    }

    render () {
        const { username, email, password, passwordConfirm, roles } = this.state;

        let rolesItems = [
            { 'id': 1, 'value': 'ROLE_SUPER_ADMIN', 'label': 'Super admin', 'identifiant': 'superamdin', 'checked': false },
            { 'id': 2, 'value': 'ROLE_ADMIN', 'label': 'Admin', 'identifiant': 'admin', 'checked': false },
            { 'id': 0, 'value': 'ROLE_USER',  'label': 'Utilisateur', 'identifiant': 'utilisateur', 'checked': false },
        ]

        return <>
            <form onSubmit={this.handleSubmit}>
                <div className="line line-2">
                    <Input valeur={username} identifiant="username" onChange={this.handleChange} >Nom utilisateur</Input>
                    <Input valeur={email} identifiant="email" onChange={this.handleChange} type="email" >Adresse e-mail</Input>
                </div>
                <div className="line">
                    <Checkbox items={rolesItems} name="roles" valeur={roles} onChange={this.handleChange}>Roles</Checkbox>
                </div>
                <div className="line line-2">
                    <Input type="password" valeur={password} identifiant="password" onChange={this.handleChange} >Mot de passe</Input>
                    <Input type="password" valeur={passwordConfirm} identifiant="passwordConfirm" onChange={this.handleChange} >Confirmer le mot de passe</Input>
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