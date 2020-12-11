import React, { Component } from 'react';

import axios             from "axios";
import Routing           from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { Page }          from "@dashboardComponents/Layout/Page";
import { LoaderElement } from "@dashboardComponents/Layout/Loader";

import UpdateList  from "@dashboardComponents/functions/updateList";

import { UserList }      from "./UserList";
import { UserCreate }    from "./UserCreate";
import { UserUpdate }    from "./UserUpdate";

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
        this.handleUpdateList = this.handleUpdateList.bind(this);
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

    handleUpdateList = (element) => {
        const { data, context } = this.state

        let newData = UpdateList.update(context, data, element);
        this.setState({
            data: newData,
            currentData: newData.slice(0,12)
        })
    }

    handleChangeContext = (context, element=null) => {
        this.setState({ context, element })
    }

    render () {
        const { loadPageError, context, loadData, data, currentData, element } = this.state;

        let content = null, havePagination = false;
        switch (context){
            case "create":
                content = <UserCreate onChangeContext={this.handleChangeContext} onUpdateList={this.handleUpdateList} />
                break;
            case "update":
                content =<UserUpdate onChangeContext={this.handleChangeContext} element={element} />
                break;
            default:
                havePagination = true;
                content = loadData ? <LoaderElement /> : <UserList onChangeContext={this.handleChangeContext} data={currentData}/>
                break;
        }

        return <>
            <Page haveLoadPageError={loadPageError}
                  havePagination={havePagination} taille={data && data.length} data={data} onUpdate={this.handleUpdateData}
            >
                {content}
            </Page>
        </>
    }
}