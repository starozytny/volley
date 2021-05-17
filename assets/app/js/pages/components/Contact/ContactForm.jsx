import React, { Component } from "react";

import axios                from "axios";
import toastr               from "toastr";
import Routing              from "@publicFolder/bundles/fosjsrouting/js/router.min";

import { Input, TextArea }  from "@dashboardComponents/Tools/Fields";
import { Button }           from "@dashboardComponents/Tools/Button";
import { Alert }            from "@dashboardComponents/Tools/Alert";
import Validateur           from "@dashboardComponents/functions/validateur";
import Formulaire           from "@dashboardComponents/functions/Formulaire";
import {RgpdInfo} from "../../../components/Tools/Rgpd";

export class ContactForm extends Component {
    constructor(props) {
        super(props);

        this.state = {
            errors: [],
            success: null,
            name: "",
            email: "",
            message: ""
        }

        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleChange = (e) => { this.setState({ [e.currentTarget.name]: e.currentTarget.value }) }

    handleSubmit = (e) => {
        e.preventDefault();
    }

    render () {
        const { errors, success, name, email, message } = this.state;

        return <form onSubmit={this.handleSubmit}>
            {success && <Alert type="info">{success}</Alert>}
            <div className="line line-2">
                <Input identifiant="name" valeur={name} errors={errors} onChange={this.handleChange}>Nom / Raison sociale</Input>
                <Input identifiant="email" valeur={email} errors={errors} onChange={this.handleChange}>Adresse e-mail</Input>
            </div>
            <div className="line">
                <TextArea identifiant="message" valeur={message} errors={errors} onChange={this.handleChange}>Message</TextArea>
            </div>
            <div className="line">
                <RgpdInfo utility="la gestion des demandes de contacts"/>
            </div>
            <div className="line">
                <Button isSubmit={true}>Envoyer le message</Button>
            </div>
        </form>
    }
}