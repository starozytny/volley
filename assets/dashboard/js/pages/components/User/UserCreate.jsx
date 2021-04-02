import React, { Component } from 'react';

import Routing           from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { UserForm }      from "./UserForm";
import { Button }        from "@dashboardComponents/Tools/Button";

export class UserCreate extends Component {
    render () {
        const { onChangeContext, onUpdateList } = this.props;

        return <>
            <div>
                <div className="toolbar">
                    <div className="item">
                        <Button outline={true} icon="left-arrow" type="primary" onClick={() => onChangeContext("list")}>Retour à la liste</Button>
                    </div>
                </div>

                <div className="form">
                    <h2>Ajouter un utilisateur</h2>
                    <UserForm
                        context="create"
                        url={Routing.generate('api_users_create')}
                        username=""
                        firstname=""
                        lastname=""
                        email=""
                        roles={[]}
                        onUpdateList={onUpdateList}
                        messageSuccess="Félicitation ! Vous avez ajouté un nouveau utilisateur !"
                    />
                </div>
            </div>
        </>
    }
}