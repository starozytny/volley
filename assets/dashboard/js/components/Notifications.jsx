import React, { Component } from "react";

import Routing    from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import Formulaire from "@dashboardComponents/functions/Formulaire";
import Sort       from "@dashboardComponents/functions/sort";

export class Notifications extends Component{
    constructor(props) {
        super();

        this.state = {
            open: false,
            data: null
        }

        this.handleOpen = this.handleOpen.bind(this);
    }

    componentDidMount = () => { Formulaire.axiosGetData(this, Routing.generate('api_notifications_index'), Sort.compareCreatedAt) }

    handleOpen = () => { this.setState({ open: !this.state.open }) }

    render() {
        const { open, data } = this.state;

        let taille = data && data.length;

        return <div className={"notif-container" + (open ? " active" : "")}>
            <div className="btn-notif" onClick={this.handleOpen}>
                <span className="icon-notification" />
                {taille !== 0 && <span className="number">{taille}</span>}
            </div>
            <div className="notif-card">
                <div className="notif-cancel">
                    <span>Notifications</span>
                    <span className="icon-cancel" onClick={this.handleOpen} />
                </div>
                <div className="notif-body">
                    {data && data.length !== 0 ? data.map(el => {
                        return <div className="item" key={el.id}>
                            <div className="item-content">
                                <div className="item-icon">
                                    <span className={"icon-" + el.icon} />
                                </div>
                                <div className="item-infos">
                                    <div className="title">{el.name}</div>
                                    <div className="createdAt">{el.createdAtAgo}</div>
                                </div>
                            </div>
                            <div className="item-actions">
                                <span className="icon-trash" />
                            </div>
                        </div>
                    }) : <div className="item"><div className="createdAt">Aucune notification</div></div>}
                </div>
                <div className="notif-all">
                    Voir toutes les notifications
                </div>
            </div>
        </div>
    }
}