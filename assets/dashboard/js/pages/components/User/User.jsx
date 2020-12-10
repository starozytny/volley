import React, { Component } from 'react';

import axios       from "axios";
import toastr      from "toastr";

import Routing     from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { Page } from "@dashboardComponents/Layout/Page";

export class User extends Component {
    constructor(props) {
        super();

        this.state = {
            loadPageError: false
        }
    }

    componentDidMount() {
        const self = this;
        axios.get(Routing.generate('api_users_index'), {})
            .then(function (response) {
                const data = response.data;

                console.log(data);
            })
            .catch(function (error) {
                self.setState({loadPageError: true});
            })
    }

    render () {
        const { loadPageError } = this.state;

        return <>
            <Page haveLoadPageError={loadPageError}>
                <div>Hello world</div>
            </Page>
        </>
    }
}