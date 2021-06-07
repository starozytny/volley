import React, { Component } from "react";

export class Cookies extends Component {
    render () {
        return <>
            <div className="cookies-title">
                <div className="biscuit">
                    <img src={'/build/app/images/biscuit.svg'} alt="Cookie illustration"/>
                </div>
                <div className="title">Nos cookies</div>
                <div className="content">
                    Notre site internet utilise des cookies pour vous offrir la meilleure expérience utilisateur.
                </div>
            </div>
            <div className="cookies-choices">
                <div>Paramétrer</div>
                <div>Tout refuser</div>
                <div>Tout accepter</div>
            </div>
        </>
    }
}