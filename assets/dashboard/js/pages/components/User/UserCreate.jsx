import React, { Component } from 'react';

import { Button }    from "@dashboardComponents/Tools/Button";
import { Input }     from "@dashboardComponents/Tools/Fields";

export class UserCreate extends Component {
    constructor(props) {
        super();

        this.state = {
            username: {value: '', error: ''}
        }

        this.handleChange = this.handleChange.bind(this);
    }

    handleChange = (e) => { this.setState({ [e.currentTarget.name]: {value: e.currentTarget.value, error: ""} }) }

    render () {
        const { onChangeContext } = this.props;
        const { username } = this.state;

        return <>
            <div>
                <div className="toolbar">
                    <div className="item">
                        <Button icon="left-arrow" type="default" onClick={() => onChangeContext("list")}>Retour à la liste</Button>
                    </div>
                </div>

                <div className="form">
                    <h2>Ajouter un utilisateur</h2>
                    <form>
                        <div className="line">
                            <Input valeur={username} identifiant="username" onChange={this.handleChange} >Nom utilisateur</Input>
                        </div>
                    </form>
                </div>


            </div>
        </>
    }
}