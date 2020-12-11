import React, { Component } from 'react';

import axios             from "axios";
import Routing           from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { Page }          from "@dashboardComponents/Layout/Page";
import { LoaderElement } from "@dashboardComponents/Layout/Loader";

import { UserItems }     from "./UserItems";
import { Button }    from "@dashboardComponents/Tools/Button";

export class User extends Component {
    constructor(props) {
        super();

        this.state = {
            context: "list",
            loadPageError: false,
            loadData: true,
            data: null,
            currentData: null,
            element: null
        }

        this.handleUpdateData = this.handleUpdateData.bind(this);
        this.handleChangeContext = this.handleChangeContext.bind(this);
    }

    componentDidMount() {
        const self = this;
        axios.get(Routing.generate('api_users_index'), {})
            .then(function (response) {
                let data = response.data;
                self.setState({ data: data, currentData: data.slice(0, 12) });
            })
            .catch(function (error) {
                self.setState({ loadPageError: true });
            })
            .then(function () {
                self.setState({ loadData: false });
            });
    }

    handleUpdateData = (data) => { this.setState({ currentData: data })  }

    handleChangeContext = (context, element=null) => {
        this.setState({ context, element })
    }

    render () {
        const { loadPageError, context, loadData, data, currentData, element } = this.state;

        let content = null;
        switch (context){
            case "update":
                content = <div>
                    <Button icon="left-arrow" onClick={() => this.handleChangeContext("list")}>Retour à la liste</Button>
                    <h2>Modifier {element.username}</h2>
                </div>
                break;
            default:
                content = loadData ? <LoaderElement /> : <div className="items-table">

                    <UserItems data={currentData} onChangeContext={this.handleChangeContext} />
                </div>
                break;
        }

        return <>
            <Page haveLoadPageError={loadPageError}
                  havePagination={true} taille={data && data.length} data={data} onUpdate={this.handleUpdateData}
            >
                {content}
            </Page>
        </>
    }
}