import React, { Component } from 'react';

import { Button }    from "@dashboardComponents/Tools/Button";

export class UserCreate extends Component {
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
                    <form>
                        <input type="text" name="username" id="username"/>
                    </form>
                </div>


            </div>
        </>
    }
}