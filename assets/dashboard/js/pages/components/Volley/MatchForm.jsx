import React, { Component } from 'react';

import axios                   from "axios";
import toastr                  from "toastr";
import Routing                 from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { Input }               from "@dashboardComponents/Tools/Fields";
import { Button }              from "@dashboardComponents/Tools/Button";
import { DatePick }            from "@dashboardComponents/Tools/DatePicker";
import { FormLayout }          from "@dashboardComponents/Layout/Elements";

import Validateur              from "@dashboardComponents/functions/validateur";
import Formulaire              from "@dashboardComponents/functions/Formulaire";

export function MatchFormulaire ({ type, onChangeContext, onUpdateList, categories, element })
{
    let title = "Ajouter un match";
    let url = Routing.generate('api_matchs_create');
    let msg = "Félicitation ! Vous avez ajouté un nouveau match !";

    if(type === "update"){
        title = "Modifier " + element.title;
        url = Routing.generate('api_matchs_update', {'id': element.id});
        msg = "Félicitation ! La mise à jour s'est réalisée avec succès !";
    }

    let form = <MatchForm
                    context={type}
                    url={url}
                    startAt={element ? element.startAtJavascript : ""}
                    team={element ? element.team : ""}
                    versus={element ? element.versus : ""}
                    localisation={element ? element.localisation : "Marseille"}
                    categories={categories}
                    onUpdateList={onUpdateList}
                    onChangeContext={onChangeContext}
                    messageSuccess={msg}
                />

    return <FormLayout onChangeContext={onChangeContext} form={form}>{title}</FormLayout>
}

export class MatchForm extends Component {
    constructor(props) {
        super(props);

        this.state = {
            startAt: props.startAt,
            team: props.team,
            versus: props.versus,
            localisation: props.localisation,
            errors: []
        }

        this.handleChange = this.handleChange.bind(this);
        this.handleChangeDateStartAt = this.handleChangeDateStartAt.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    componentDidMount() {
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
        document.getElementById("team").focus()
    }

    handleChange = (e) => { this.setState({[e.currentTarget.name]: e.currentTarget.value}) }

    handleChangeDateStartAt = (e) => { this.setState({ startAt: e }) }

    handleSubmit = (e) => {
        e.preventDefault();

        const { url, messageSuccess } = this.props;
        const { startAt, team, versus, localisation } = this.state;

        this.setState({ success: false})

        let paramsToValidate = [
            {type: "text", id: 'startAt', value: startAt},
            {type: "text", id: 'team', value: team},
            {type: "text", id: 'versus', value: versus},
            {type: "text", id: 'localisation', value: localisation}
        ];

        // validate global
        let validate = Validateur.validateur(paramsToValidate)

        // check validate success
        if(!validate.code){
            this.setState({ errors: validate.errors });
        }else{
            let formData = new FormData();
            let start = new Date(startAt);
            formData.append('startAt', start.getFullYear() + "-" + (start.getMonth() + 1) + "-" + start.getDate());
            formData.append('team', team);
            formData.append('versus', versus);
            formData.append('localisation', localisation);

            Formulaire.loader(true);
            let self = this;
            axios({ method: "POST", url: url, data: formData })
                .then(function (response) {
                    let data = response.data;
                    self.props.onUpdateList(data);
                    self.props.onChangeContext("list");
                    toastr.info(messageSuccess);
                })
                .catch(function (error) {
                    Formulaire.displayErrors(self, error);
                })
                .then(() => {
                    Formulaire.loader(false);
                })
            ;
        }
    }

    render () {
        const { context } = this.props;
        const { errors, startAt, team, versus, localisation } = this.state;

        return <>
            <form onSubmit={this.handleSubmit}>
                <div className="line">
                    <DatePick identifiant="startAt" valeur={startAt} minDate={new Date()} errors={errors} onChange={this.handleChangeDateStartAt}>Date de la rencontre</DatePick>
                </div>

                <div className="line line-2">
                    <Input valeur={team} identifiant="team" errors={errors} onChange={this.handleChange} >Equipe 1</Input>
                    <Input valeur={versus} identifiant="versus" errors={errors} onChange={this.handleChange} >Equipe 2</Input>
                </div>

                <div className="line">
                    <Input valeur={localisation} identifiant="localisation" errors={errors} onChange={this.handleChange} >Localisation</Input>
                </div>

                <div className="line">
                    <div className="form-button">
                        <Button isSubmit={true}>{context === "create" ? "Ajouter le match" : 'Modifier le match'}</Button>
                    </div>
                </div>
            </form>
        </>
    }
}