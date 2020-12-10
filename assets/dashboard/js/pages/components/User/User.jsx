import React, { Component } from 'react';

import axios       from "axios";
import toastr      from "toastr";

import Routing     from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { Page } from "@dashboardComponents/Layout/Page";
import { LoaderElement } from "@dashboardComponents/Layout/Loader";

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
                const data = response.data;
                self.setState({ data });
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
                {loadData ? <LoaderElement /> :
                    <div>Hello world</div>
                }
            </Page>
        </>
    }
}