import React, { Component } from 'react';

import { UserForm }  from "./UserForm";

import { Button }    from "@dashboardComponents/Tools/Button";
import { Input }     from "@dashboardComponents/Tools/Fields";

export class UserCreate extends Component {
    constructor(props) {
        super();
    }

    render () {
        const { onChangeContext } = this.props;

        return <>
            <div>
                <div className="toolbar">
                    <div className="item">
                        <Button icon="left-arrow" type="default" onClick={() => onChangeContext("list")}>Retour à la liste</Button>
                    </div>
                </div>

                <div className="form">
                    <h2>Ajouter un utilisateur</h2>
                    <UserForm
                        username=""
                        email=""
                    />
                </div>
            </div>
        </>
    }
}