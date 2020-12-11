import React, { Component } from 'react';

import Routing           from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { UserForm }  from "./UserForm";

import { Button }    from "@dashboardComponents/Tools/Button";
import { Input }     from "@dashboardComponents/Tools/Fields";

export class UserCreate extends Component {
    constructor(props) {
        super();
    }

    render () {
        const { onChangeContext, onUpdateList } = this.props;

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
                        type="create"
                        url={Routing.generate('api_users_create')}
                        username=""
                        email=""
                        onUpdateList={onUpdateList}
                    />
                </div>
            </div>
        </>
    }
}