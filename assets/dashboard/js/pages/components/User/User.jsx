import React, { Component } from 'react';

import axios             from "axios";
import Routing           from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { Page }          from "@dashboardComponents/Layout/Page";
import { LoaderElement } from "@dashboardComponents/Layout/Loader";

import { UserItems } from "./UserItems";

export class User extends Component {
    constructor(props) {
        super();

        this.state = {
            loadPageError: false,
            loadData: true,
            data: null,
            currentData: null
        }

        this.handleUpdateData = this.handleUpdateData.bind(this);
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

    /**
     *
     * @param data
     */
    handleUpdateData = (data) => { this.setState({ currentData: data })  }

    render () {
        const { loadPageError, loadData, data, currentData } = this.state;

        return <>
            <Page haveLoadPageError={loadPageError}
                  havePagination={true} taille={data && data.length} data={data} onUpdate={this.handleUpdateData}
            >
                {loadData ? <LoaderElement /> : <UserItems data={currentData} />}
            </Page>
        </>
    }
}