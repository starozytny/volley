import React, { Component } from 'react';

import axios             from "axios";
import toastr            from "toastr";
import Swal              from "sweetalert2";
import Routing           from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { Page }          from "@dashboardComponents/Layout/Page";
import { LoaderElement } from "@dashboardComponents/Layout/Loader";

import UpdateList        from "@dashboardComponents/functions/updateList";
import Sort              from "@dashboardComponents/functions/sort";
import SwalOptions       from "@dashboardComponents/functions/swalOptions";
import Loader            from "@dashboardComponents/functions/loader";

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
        this.handleDelete = this.handleDelete.bind(this);
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

    handleUpdateList = (element, newContext=null) => {
        const { data, context } = this.state

        let nContext = (newContext !== null) ? newContext : context;
        let newData = UpdateList.update(nContext, data, element);
        newData.sort(Sort.compareUsername)

        this.setState({
            data: newData,
            currentData: newData.slice(0,12),
            element: element
        })
    }

    handleChangeContext = (context, element=null) => {
        this.setState({ context, element })
    }

    handleDelete = (element) => {
        let self = this;
        Swal.fire(SwalOptions.options('Supprimer cet utilisateur ?', 'Cette action est irréversible.'))
            .then((result) => {
                if (result.isConfirmed) {

                    Loader.loader(true);
                    axios.delete(Routing.generate('api_users_delete', {'id': element.id}), {})
                        .then(function (response) {
                            Swal.fire(response.data.message, '', 'success');
                            self.handleUpdateList(element, "delete");
                        })
                        .catch(function (error) {
                            if(error.response.data.message){
                                toastr.error(error.response.data.message)
                            }else{
                                toastr.error("Une erreure est survenue, veuillez contacter le support.")
                            }
                        })
                        .then(() => {
                            Loader.loader(false);
                        })

                }
            })
    }

    render () {
        const { loadPageError, context, loadData, data, currentData, element } = this.state;

        let content = null, havePagination = false;
        switch (context){
            case "create":
                content = <UserCreate onChangeContext={this.handleChangeContext} onUpdateList={this.handleUpdateList} />
                break;
            case "update":
                content =<UserUpdate onChangeContext={this.handleChangeContext} onUpdateList={this.handleUpdateList} element={element} />
                break;
            default:
                havePagination = true;
                content = loadData ? <LoaderElement /> : <UserList onChangeContext={this.handleChangeContext} onDelete={this.handleDelete} data={currentData} />
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