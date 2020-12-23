import React, { Component } from "react";

import {Checkbox, Input, Radiobox, TextArea} from "@dashboardComponents/Tools/Fields";
import { Button }   from "@dashboardComponents/Tools/Button";

import Validator    from "@dashboardComponents/functions/validateur";

export class StyleguideForm extends Component {
    constructor(props) {
        super();

        this.state = {
            errors: [],
            username: "",
            email: "",
            message: "",
            roles: ["ROLE_USER"], // default : ["ROLE_USER"]
            sexe: "",             // default : 0
        }

        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleChange = (e) => {
        const { roles } = this.state;

        let name = e.currentTarget.name;
        let value = e.currentTarget.value;

        if(name === "roles"){
            value = (e.currentTarget.checked) ? [...roles, ...[value]] : roles.filter(v => v !== value)
        }

        this.setState({ [name]: value })
    }

    handleSubmit = (e) => {
        e.preventDefault();

        const { username, email, message, roles, sexe } = this.state;

        let validate = Validator.validateur([
            {type: "text", id: 'username', value: username},
            {type: "email", id: 'email', value: email},
            {type: "text", id: 'message', value: message},
            {type: "array", id: 'roles', value: roles},
            {type: "text", id: 'sexe', value: sexe},
        ])

        if(!validate.code){
            this.setState({ errors: validate.errors });
        }else{
            console.log("Axios")
            this.setState({ errors: []});
        }
    }

    render () {
        const { errors, username, email, message, roles, sexe } = this.state;

        let checkboxItems = [
            { 'id': 0, 'value': 'ROLE_USER', 'label': 'Utilisateur', 'identifiant': 'utilisateur' },
            { 'id': 2, 'value': 'ROLE_ADMIN', 'label': 'Admin', 'identifiant': 'admin' }
        ]

        let radioboxItems = [
            { 'id': 0, 'value': 0, 'label': 'Homme', 'identifiant': 'homme' },
            { 'id': 1, 'value': 1, 'label': 'Femme', 'identifiant': 'femme' }
        ]

        return (
            <section>
                <h2>Formulaire</h2>
                <div className="form-items">
                    <form onSubmit={this.handleSubmit}>

                        <div className="line line-2">
                            <Input identifiant="username" valeur={username} errors={errors} onChange={this.handleChange}>Username</Input>
                            <Input identifiant="email" valeur={email} errors={errors} onChange={this.handleChange} type="email">Adresse e-mail</Input>
                        </div>

                        <div className="line">
                            <TextArea identifiant="message" valeur={message} errors={errors} onChange={this.handleChange}>Message</TextArea>
                        </div>

                        <div className="line line-2">
                            <Checkbox items={checkboxItems} identifiant="roles" valeur={roles} errors={errors} onChange={this.handleChange} >Roles</Checkbox>
                            <Radiobox items={radioboxItems} identifiant="sexe" valeur={sexe} errors={errors} onChange={this.handleChange} >Sexe</Radiobox>
                        </div>

                        <div className="form-button">
                            <Button isSubmit={true}>Test Error</Button>
                        </div>

                    </form>
                </div>
            </section>
        )
    }
}