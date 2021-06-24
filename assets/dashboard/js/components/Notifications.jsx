import React, { Component } from "react";

import axios      from "axios";
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
        this.handleSeen = this.handleSeen.bind(this);
    }

    componentDidMount = () => { Formulaire.axiosGetData(this, Routing.generate('api_notifications_index'), Sort.compareCreatedAt) }

    handleUpdateList = (element, newContext = null) => {
        const { data } = this.state
        Formulaire.updateData(this, Sort.compareCreatedAt, newContext, "update", data, element);
    }

    handleOpen = () => { this.setState({ open: !this.state.open }) }

    handleSeen = (element) => {
        if(!element.isSeen){
            const self = this;
            axios.post(Routing.generate('api_notifications_isSeen', {'id': element.id}), {})
                .then(function (response) {
                    let data = response.data;
                    self.handleUpdateList(data, 'update');
                })
                .catch(function (error) {
                    Formulaire.displayErrors(self, error)
                })
            ;
        }
    }

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
                            <div className="item-content" onClick={() => this.handleSeen(el)}>
                                <div className="item-icon">
                                    <span className={"icon-" + el.icon} />
                                </div>
                                <div className="item-infos">
                                    <div className="title">{!el.isSeen && <span className="toSee" />} {el.name}</div>
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