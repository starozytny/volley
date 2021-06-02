import React, { Component } from 'react';

import { PageError }        from "./PageError";
import { Pagination }       from "./Pagination";
import { LoaderElement }    from "@dashboardComponents/Layout/Loader";

import Sort                 from "@dashboardComponents/functions/sort";
import Formulaire           from "@dashboardComponents/functions/Formulaire";

export class Page extends Component {
    constructor(props) {
        super(props);

        this.pagination = React.createRef();
    }

    render () {
        const { haveLoadPageError, children,
            havePagination, perPage = "10", taille, data,
        } = this.props;

        let hPagination = (havePagination && data && data.length !== 0);

        return <>
            {haveLoadPageError && <PageError />}
            <div className="main-content">
                {children}
                <Pagination ref={this.pagination} havePagination={hPagination} perPage={perPage} taille={taille} items={data}
                            onUpdate={(items) => this.props.onUpdate(items)}/>
            </div>

        </>
    }
}

export class Layout extends Component {
    constructor(props) {
        super(props);

        this.state = {
            context: "list",
            loadPageError: false,
            loadData: true,
            data: null,
            currentData: null,
            element: null,
            perPage: props.perPage
        }

        this.page = React.createRef();

        this.handleUpdateData = this.handleUpdateData.bind(this);
        this.handleChangeContext = this.handleChangeContext.bind(this);
        this.handleUpdateList = this.handleUpdateList.bind(this);
        this.handleSearch = this.handleSearch.bind(this);
    }

    componentDidMount() { this.props.onGetData(this); }

    handleUpdateData = (data) => { this.setState({ currentData: data })  }

    handleChangeContext = (context, element=null) => {
        this.setState({ context, element });
        if(context === "list"){
            this.page.current.pagination.current.handleComeback()
        }
    }

    handleUpdateList = (element, newContext=null) => {
        const { data, context, perPage } = this.state
        Formulaire.updateDataPagination(this, Sort.compareCreatedAt, newContext, context, data, element, perPage);
    }

    handleSearch = (search, searchFunction) => {
        const { dataImmuable, perPage } = this.state;

        if(search === "") {
            this.setState({ data: dataImmuable, currentData: dataImmuable.slice(0, perPage) });
        }else{
            let newData = searchFunction(dataImmuable, search);
            this.setState({ data: newData, currentData: newData.slice(0, perPage) });
        }
    }

    render () {
        const { onContentList, onContentCreate, onContentUpdate } = this.props;
        const { loadPageError, context, loadData, data, currentData, element } = this.state;

        let content, havePagination = false;
        switch (context){
            case "create":
                content = onContentCreate(this.handleChangeContext, this.handleUpdateList)
                break;
            case "update":
                content = onContentUpdate(this.handleChangeContext, this.handleUpdateList, element)
                break;
            default:
                havePagination = true;
                content = loadData ? <LoaderElement /> : onContentList(currentData, this.handleChangeContext)
                break;
        }

        if(data && data.length <= 0){
            havePagination = false;
        }

        return <>
            <Page ref={this.page} haveLoadPageError={loadPageError}
                  havePagination={havePagination} taille={data && data.length} data={data} onUpdate={this.handleUpdateData}
            >
                {content}
            </Page>
        </>
    }
}