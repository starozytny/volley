import React, { Component } from "react";

import { Checkbox, Input, Radiobox, Select, TextArea } from "@dashboardComponents/Tools/Fields";
import { DatePick, DateTimePick, TimePick }            from "@dashboardComponents/Tools/DatePicker";
import { Drop }                                        from "@dashboardComponents/Tools/Drop"
import { Button }                                      from "@dashboardComponents/Tools/Button";

import Validator    from "@dashboardComponents/functions/validateur";
import Sanitaze     from "@dashboardComponents/functions/sanitaze";;

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
            pays: "",  // default : "France"
            birthday: "",
            createAt: "",
            arrived: "",
            postalCode: "",
            arrayPostalCode: [],
            city: "",
            avatar: "",
            files: [],
        }

        this.inputAvatar = React.createRef();
        this.inputFiles = React.createRef();

        this.handleChange = this.handleChange.bind(this);
        this.handleChangePostalCodeCity = this.handleChangePostalCodeCity.bind(this);
        this.handleChangeDateCreateAt = this.handleChangeDateCreateAt.bind(this);
        this.handleChangeDateCreateAt = this.handleChangeDateCreateAt.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    componentDidMount = () => { Sanitaze.getPostalCodes(this); } // fill arrayPostalCode

    handleChange = (e) => {
        const { roles } = this.state;

        let name = e.currentTarget.name;
        let value = e.currentTarget.value;

        if(name === "roles"){
            value = (e.currentTarget.checked) ? [...roles, ...[value]] : roles.filter(v => v !== value)
        }

        this.setState({ [name]: value })
    }

    handleChangePostalCodeCity = (e) => {
        const { arrayPostalCode } = this.state;

        let name = e.currentTarget.name;
        let value = e.currentTarget.value;

        if(value.length <= 5){
            this.setState({ [name]: value })

            let v = ""
            if(arrayPostalCode.length !== 0){
                v = arrayPostalCode.filter(el => el.cp === value)

                if(v.length === 1){
                    this.setState({ city: v[0].city })
                }
            }
        }
    }

    handleChangeDateBirthday = (e) => { this.setState({ birthday: e }) }
    handleChangeDateCreateAt = (e) => { this.setState({ createAt: e }) }
    handleChangeDateArrived = (e) => { this.setState({ arrived: e }) }

    handleSubmit = (e) => {
        e.preventDefault();

        const { username, email, message, roles, sexe, pays, birthday, createAt, arrived, postalCode, city, avatar, files } = this.state;

        let validate = Validator.validateur([
            {type: "text", id: 'username', value: username},
            {type: "email", id: 'email', value: email},
            {type: "text", id: 'message', value: message},
            {type: "array", id: 'roles', value: roles},
            {type: "text", id: 'sexe', value: sexe},
            {type: "text", id: 'pays', value: pays},
            {type: "text", id: 'birthday', value: birthday},
            {type: "text", id: 'createAt', value: createAt},
            {type: "text", id: 'arrived', value: arrived},
            {type: "text", id: 'postalCode', value: postalCode},
            {type: "text", id: 'city', value: city},
            {type: "array", id: 'avatar', value: avatar},
            {type: "array", id: 'files', value: files},
        ])

        console.log(this.inputAvatar.current.drop.current.files)
        console.log(this.inputFiles.current.drop.current.files)

        if(!validate.code){
            this.setState({ errors: validate.errors });
        }else{
            this.setState({ errors: []});
        }
    }

    render () {
        const { errors, username, email, message, roles, sexe, pays, birthday, createAt, arrived, postalCode, city } = this.state;

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
                            <Select items={selectItems} identifiant="pays" valeur={pays} errors={errors} onChange={this.handleChange}>De quel pays viens-tu ?</Select>
                        </div>

                        <div className="line line-3">
                            <DatePick identifiant="birthday" valeur={birthday} errors={errors} onChange={this.handleChangeDateBirthday}>Date de naissance</DatePick>
                            <DateTimePick identifiant="createAt" valeur={createAt} errors={errors} onChange={this.handleChangeDateCreateAt}>Date de création</DateTimePick>
                            <TimePick identifiant="arrived" valeur={arrived} errors={errors} onChange={this.handleChangeDateArrived}>Heure d'arrivée</TimePick>
                        </div>

                        <div className="line line-2">
                            <Input identifiant="postalCode" valeur={postalCode} errors={errors} onChange={this.handleChangePostalCodeCity} type="number" >Code postal</Input>
                            <Input identifiant="city" valeur={city} errors={errors} onChange={this.handleChange}>Ville</Input>
                        </div>

                        <div className="line line-2">
                            <Drop ref={this.inputAvatar} identifiant="avatar" errors={errors} accept={"image/*"} maxFiles={1}
                                  label="Téléverser un avatar" labelError="Seules les images sont acceptées.">Fichier</Drop>
                            <Drop ref={this.inputFiles} identifiant="files" errors={errors} accept={"image/*"} maxFiles={3}
                                  label="Téléverser des fichiers" labelError="Seules les images sont acceptées.">Fichier</Drop>
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