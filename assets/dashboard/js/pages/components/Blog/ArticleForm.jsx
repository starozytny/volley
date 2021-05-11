import React, { Component } from 'react';

import axios                   from "axios";
import toastr                  from "toastr";

import { Input }               from "@dashboardComponents/Tools/Fields";
import { Button }              from "@dashboardComponents/Tools/Button";
import { Trumb }               from "@dashboardComponents/Tools/Trumb";

import Validateur              from "@dashboardComponents/functions/validateur";
import Formulaire              from "@dashboardComponents/functions/Formulaire";

export class ArticleForm extends Component {
    constructor(props) {
        super(props);

        this.state = {
            title: props.title,
            introduction: { value: props.introduction ? props.introduction : "", html: props.introduction ? props.introduction : "" },
            content: { value: props.content ? props.content : "", html: props.content ? props.content : "" },
            errors: []
        }

        this.handleChange = this.handleChange.bind(this);
        this.handleChangeTrumb = this.handleChangeTrumb.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    componentDidMount() {
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
        document.getElementById("title").focus()
    }

    handleChange = (e) => { this.setState({[e.currentTarget.name]: e.currentTarget.value}) }

    handleChangeTrumb = (e) => {
        let val = this.state.introduction.value;
        if(e.currentTarget.id === "content"){
            val = this.state.content.value;
        }
        this.setState({ [e.currentTarget.id]: { value: val, html: e.currentTarget.innerHTML } })
    }

    handleSubmit = (e) => {
        e.preventDefault();

        const { context, url, messageSuccess } = this.props;
        const { title } = this.state;

        this.setState({ success: false})

        let method = "PUT";
        let paramsToValidate = [
            {type: "text", id: 'title', value: title}
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
        const { errors, title, introduction, content } = this.state;

        return <>
            <form onSubmit={this.handleSubmit}>
                <div className="line">
                    <Input valeur={title} identifiant="title" errors={errors} onChange={this.handleChange} >Titre de l'article</Input>
                </div>

                <div className="line">
                    <Trumb valeur={introduction.value} identifiant="introduction" errors={errors} onChange={this.handleChangeTrumb} >Introduction</Trumb>
                </div>

                <div className="line">
                    <Trumb valeur={content.value} identifiant="content" errors={errors} onChange={this.handleChangeTrumb} >Contenu de l'article</Trumb>
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