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
            data: null
        }
    }

    componentDidMount() {
        const self = this;
        axios.get(Routing.generate('api_users_index'), {})
            .then(function (response) {
                self.setState({ data: response.data });
            })
            .catch(function (error) {
                self.setState({ loadPageError: true });
            })
            .then(function () {
                self.setState({ loadData: false });
            });
    }

    render () {
        const { loadPageError, loadData, data } = this.state;

        return <>
            <Page haveLoadPageError={loadPageError}>
                {loadData ? <LoaderElement /> : <UserItems data={data} />}
            </Page>
        </>
    }
}