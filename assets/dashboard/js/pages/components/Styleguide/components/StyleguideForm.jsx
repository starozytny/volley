import React, { Component } from "react";

import { Checkbox, Input, Radiobox, Select, TextArea } from "@dashboardComponents/Tools/Fields";
import { Button }   from "@dashboardComponents/Tools/Button";
import {DatePick, DateTimePick} from "@dashboardComponents/Tools/DatePicker";

import Validator    from "@dashboardComponents/functions/validateur";

export class StyleguideForm extends Component {
    constructor(props) {
        super();

        this.state = {
            errors: [],
            username: "",
            email: "",
            message: "",
            roles: [], // default : ["ROLE_USER"]
            sexe: "",  // default : 0
            city: "",  // default : "France"
            birthday: "",
            createAt: "",
        }

        this.handleChange = this.handleChange.bind(this);
        this.handleChangeDateBirthday = this.handleChangeDateBirthday.bind(this);
        this.handleChangeDateCreateAt = this.handleChangeDateCreateAt.bind(this);
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

    handleChangeDateBirthday = (e) => { this.setState({ birthday: e }) }
    handleChangeDateCreateAt = (e) => { this.setState({ createAt: e }) }

    handleSubmit = (e) => {
        e.preventDefault();

        const { username, email, message, roles, sexe, city, birthday, createAt } = this.state;

        let validate = Validator.validateur([
            {type: "text", id: 'username', value: username},
            {type: "email", id: 'email', value: email},
            {type: "text", id: 'message', value: message},
            {type: "array", id: 'roles', value: roles},
            {type: "text", id: 'sexe', value: sexe},
            {type: "text", id: 'city', value: city},
            {type: "text", id: 'birthday', value: birthday},
            {type: "text", id: 'createAt', value: createAt},
        ])

        if(!validate.code){
            this.setState({ errors: validate.errors });
        }else{
            console.log("Axios")
            this.setState({ errors: []});
        }
    }

    render () {
        const { errors, username, email, message, roles, sexe, city, birthday, createAt } = this.state;

        let checkboxItems = [
            { 'value': 'ROLE_USER', 'label': 'Utilisateur', 'identifiant': 'utilisateur' },
            { 'value': 'ROLE_ADMIN', 'label': 'Admin', 'identifiant': 'admin' }
        ]

        let radioboxItems = [
            { 'value': 0, 'label': 'Homme', 'identifiant': 'homme' },
            { 'value': 1, 'label': 'Femme', 'identifiant': 'femme' }
        ]

        let selectItems = [
            { 'value': 0, 'label': 'France', 'identifiant': 'france' },
            { 'value': 1, 'label': 'Allemagne', 'identifiant': 'allemagne' },
            { 'value': 2, 'label': 'Japon', 'identifiant': 'japon' },
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
                            <Checkbox items={checkboxItems} identifiant="roles" valeur={roles} errors={errors} onChange={this.handleChange}>Roles</Checkbox>
                            <Radiobox items={radioboxItems} identifiant="sexe" valeur={sexe} errors={errors} onChange={this.handleChange}>Sexe</Radiobox>
                        </div>

                        <div className="line">
                            <Select items={selectItems} identifiant="city" valeur={city} errors={errors} onChange={this.handleChange}>De quel pays viens-tu ?</Select>
                        </div>

                        <div className="line line-2">
                            <DatePick identifiant="birthday" valeur={birthday} errors={errors} onChange={this.handleChangeDateBirthday}>Date de naissance</DatePick>
                            <DateTimePick identifiant="createAt" valeur={createAt} errors={errors} onChange={this.handleChangeDateCreateAt}>Date de création</DateTimePick>
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