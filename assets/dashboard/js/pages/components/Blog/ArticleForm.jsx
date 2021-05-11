import React, { Component } from 'react';

import axios                   from "axios";

import { Input, Checkbox }     from "@dashboardComponents/Tools/Fields";
import { Alert }               from "@dashboardComponents/Tools/Alert";
import { Button }              from "@dashboardComponents/Tools/Button";

import Validateur              from "@dashboardComponents/functions/validateur";
import Formulaire              from "@dashboardComponents/functions/Formulaire";
import {Trumb} from "@dashboardComponents/Tools/Trumb";

export class ArticleForm extends Component {
    constructor(props) {
        super(props);

        this.state = {
            title: props.title,
            introduction: props.introduction,
            content: props.content,
            errors: [],
            success: false
        }

        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    componentDidMount() {
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
        document.getElementById("title").focus()
    }

    handleChange = (e) => {
        let name = e.currentTarget.name;
        let value = e.currentTarget.value;
        this.setState({[name]: value})
    }

    handleSubmit = (e) => {
        e.preventDefault();

        const { context, url, messageSuccess } = this.props;
        const { title, introduction, content } = this.state;

        this.setState({ success: false})

        let method = "PUT";
        let paramsToValidate = [
            {type: "text", id: 'title', value: title},
            {type: "text", id: 'introduction', value: introduction},
            {type: "text", id: 'content', value: content},
        ];
        if(context === "create"){
            method = "POST";
        }

        // validate global
        let validate = Validateur.validateur(paramsToValidate)

        // check validate success
        if(!validate.code){
            this.setState({ errors: validate.errors });
        }else{
            Formulaire.loader(true);
            let self = this;
            axios({ method: method, url: url, data: self.state })
                .then(function (response) {
                    let data = response.data;
                    self.props.onUpdateList(data);
                    self.setState({ success: messageSuccess, errors: [] });
                    if(context === "create"){
                        self.setState( {
                            title: '',
                            introduction: '',
                            content: '',
                        })
                    }
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
        const { errors, success, title, introduction, content } = this.state;

        return <>
            <form onSubmit={this.handleSubmit}>

                {success !== false && <Alert type="info">{success}</Alert>}

                <div className="line">
                    <Input valeur={title} identifiant="title" errors={errors} onChange={this.handleChange} >Titre de l'article</Input>
                </div>

                <div className="line">
                    <Trumb valeur={introduction} identifiant="introduction" errors={errors} onChange={this.handleChange} >Introduction</Trumb>
                </div>

                <div className="line">
                    <Trumb valeur={content} identifiant="content" errors={errors} onChange={this.handleChange} >Contenu de l'article</Trumb>
                </div>

                <div className="line">
                    <div className="form-button">
                        <Button isSubmit={true}>{context === "create" ? "Ajouter l'article" : 'Modifier l\'article'}</Button>
                    </div>
                </div>
            </form>
        </>
    }
}