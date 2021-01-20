import React, { Component } from 'react';

import Routing           from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { Input }        from "@dashboardComponents/Tools/Fields";
import { Button }       from "@dashboardComponents/Tools/Button";
import { Drop }         from "@dashboardComponents/Tools/Drop";
import Formulaire       from "@dashboardComponents/functions/Formulaire";

function getBase64(file, self) {
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function () {
        self.setState({logo: {value: reader.result} })
    };
    reader.onerror = function (error) {
        console.log('Error: ', error);
    };
}

export class SettingsForm extends Component {
    constructor(props) {
        super(props);

        this.state = {
            errors: [],
            success: false,
            websiteName: props.data ? props.data.websiteName : "",
            emailGlobal: props.data ? props.data.emailGlobal : "",
            emailContact: props.data ? props.data.emailContact : "",
            emailRgpd: props.data ? props.data.emailRgpd : "",
            logoMail: props.data ? props.data.logoMail : "",
        }

        this.inputLogoMail = React.createRef();

        this.handleChange = this.handleChange.bind(this);
        this.handleGetFile  = this.handleGetFile .bind(this);
        this.handleSubmit   = this.handleSubmit  .bind(this);
    }

    handleChange = (e) => { this.setState({[ e.currentTarget.name]: {value: e.currentTarget.value}})  }
    handleGetFile = (e) => { getBase64(e.file, this) }

    handleSubmit = (e) => {
        e.preventDefault();
    }

    render () {
        const { errors, success, websiteName, emailGlobal, emailContact, emailRgpd, logoMail } = this.state;

        return <>
            <div className="form">
                <form>
                    <div className="line line-2">
                        <Input valeur={websiteName} identifiant="websiteName" errors={errors} onChange={this.handleChange}>Nom du site</Input>
                        <Input valeur={emailGlobal} identifiant="emailGlobal" errors={errors} onChange={this.handleChange} type="email">E-mail expéditeur global</Input>
                    </div>

                    <div className="line line-2">
                        <Input valeur={emailContact} identifiant="emailContact" errors={errors} onChange={this.handleChange} type="email">E-mail expéditeur contact</Input>
                        <Input valeur={emailRgpd} identifiant="emailRgpd" errors={errors} onChange={this.handleChange} type="email">E-mail expéditeur RGPD</Input>
                    </div>

                    <div className="line">
                        <Drop ref={this.inputLogoMail} identifiant="inputLogoMail" errors={errors} accept={"image/*"} maxFiles={1}
                              label="Téléverser votre logo" labelError="Seules les images sont acceptées.">Logo</Drop>
                    </div>

                    <div className="line">
                        <div className="form-button">
                            <Button isSubmit={true}>Valider les paramètres</Button>
                        </div>
                    </div>
                </form>
            </div>
        </>
    }
}