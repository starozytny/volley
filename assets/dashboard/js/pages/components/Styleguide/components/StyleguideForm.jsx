import React, { Component } from "react";

import { Input }    from "@dashboardComponents/Tools/Fields";
import { Button }   from "@dashboardComponents/Tools/Button";

import Validator    from "@dashboardComponents/functions/validateur";

export class StyleguideForm extends Component {
    constructor(props) {
        super();

        this.state = {
            errors: [],
            username: "",
            email: ""
        }

        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleChange = (e) => {
        this.setState({ [e.currentTarget.name]: e.currentTarget.value })
    }

    handleSubmit = (e) => {
        e.preventDefault();

        const { username, email } = this.state;

        let validate = Validator.validateur([
            {type: "text", id: 'username', value: username},
            {type: "email", id: 'email', value: email},
        ])

        if(!validate.code){
            this.setState({ errors: validate.errors });
        }else{
            console.log("Axios")
            this.setState({ errors: []});
        }
    }

    render () {
        const { errors, username, email } = this.state;

        return (
            <section>
                <h2>Formulaire</h2>
                <div className="form-items">
                    <form onSubmit={this.handleSubmit}>
                        <div className="line line-2">
                            <Input identifiant="username" valeur={username} errors={errors} onChange={this.handleChange}>Username</Input>
                            <Input identifiant="email" valeur={email} errors={errors} onChange={this.handleChange} type="email">Adresse e-mail</Input>
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