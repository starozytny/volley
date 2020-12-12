import React, {Component} from 'react';
import ReactPaginate      from 'react-paginate';

export class Pagination extends Component {
    constructor (props) {
        super(props)

        this.state = {
            offset: 0,
            currentPage: 0,
            perPage: props.perPage !== undefined ? props.perPage : 20
        }

        this.handleClick = this.handleClick.bind(this);
        this.handleComeback = this.handleComeback.bind(this);
    }

    componentDidMount() {
        localStorage.setItem('user.pagination', "0");
    }

    handleClick = (e) => {
        const { perPage, items } = this.props;

        const selectedPage = e.selected;
        const offset = selectedPage * perPage;

        if(items !== null){
            this.setState({ currentPage: selectedPage, offset: offset })
            this.props.onUpdate(items.slice(offset, offset + parseInt(perPage)))
            localStorage.setItem('user.pagination', selectedPage);
        }
    }

    handleComeback = () => {
        const { perPage, items } = this.props;

        const selectedPage = localStorage.getItem('user.pagination');
        const offset = selectedPage * perPage;

        this.setState({ currentPage: selectedPage, offset: offset })
        this.props.onUpdate(items.slice(offset, offset + parseInt(perPage)))
    }

    handlePageOne = () => {
        const { perPage, items } = this.props;

        const offset = 0;

        this.setState({ currentPage: 0, offset: offset })
        this.props.onUpdate(items.slice(offset, offset + parseInt(perPage)))
    }

    render () {
        const { havePagination, taille } = this.props
        const { perPage, currentPage } = this.state

        return <>
            {havePagination && <ReactPaginate
                previousLabel={<span className="icon-left-arrow" />}
                nextLabel={<span className="icon-right-arrow" />}
                breakLabel={'...'}
                breakClassName={'break-me'}
                pageCount={Math.ceil(taille / perPage)}
                marginPagesDisplayed={1}
                pageRangeDisplayed={3}
                onPageChange={this.handleClick}
                containerClassName={'pagination'}
                subContainerClassName={'pages pagination'}
                activeClassName={'active'}
                initialPage={parseInt(currentPage)}
                forcePage={parseInt(currentPage)}
            />}
        </>
    }
}
