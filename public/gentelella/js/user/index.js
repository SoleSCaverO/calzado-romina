var SearchPanel = React.createClass({
    render: function() {
        return (
            <div className="form-inline col-md-8">
                <div className="col-md-8 input-group">
                    <span class="input-group-addon">Filtro</span>
                    <input ref='search' className='form-control' type='text' value={this.props.search} onChange={this.onSearchChanged} />
                    {this.props.search?<button onClick={this.props.onClearSearch}>x</button>:null}
                </div>
            </div>
        )
    },
    onSearchChanged: function() {
        var query = this.refs.search.value;
        this.props.onSearchChanged(query);
    }
});

var UsersTableRow = React.createClass({
    render: function() {
        return (
            <tr>
                <td>{this.props.user.email}</td>
                <td>{this.props.user.name}</td>
                <td>{this.props.user.role.name}</td>
                <td>
                    <button type="button" className="btn btn-primary" onClick={this.onEdit}><i className="fa fa-pencil"></i></button>
                </td>
            </tr>
        );
    },
    onEdit: function() {
        this.props.handleEditClickPanel(this.props.user.id);
    }
});

var UsersPagination = React.createClass({
    render: function() {
        var prev_button, next_button;

        if (this.props.current_page==1)
            prev_button = <li className="disabled"><span>«</span></li>;
        else prev_button = <li><span>«</span></li>;

        if (this.props.current_page==this.props.last_page)
            next_button = <li className="disabled"><span>»</span></li>;
        else next_button = <li><span>»</span></li>;

        var buttons = [];
        for (var i=1; i<=this.props.last_page; ++i) {
            var button;
            if (i == this.props.current_page)
                button = <li className="active"><span>{this.props.current_page}</span></li>;
            else
                button = <li><a href="#" onClick={this.onPageSelected}>{i}</a></li>;
            buttons.push(button);
        }

        return <ul className="pagination">
            {prev_button}

            {buttons}

            {next_button}
        </ul>
    },
    onPageSelected: function(e) {
        e.preventDefault();

        this.props.handlePageChange(e.currentTarget.innerHTML);
    }
});

var UsersTable = React.createClass({
    render: function() {
        var rows = [];
        this.props.users.forEach(function(user) {
            rows.push(<UsersTableRow key={user.id} user={user} handleEditClickPanel={this.props.handleEditClickPanel} handleDeleteClickPanel={this.props.handleDeleteClickPanel} />);
        }.bind(this));
        return (
            <table className="table table-condensed table-hover">
                <thead>
                <tr>
                    <th>E-mail</th>
                    <th>Nombres</th>
                    <th>Rol</th>
                    <th>Acción</th>
                </tr>
                </thead>
                <tbody>{rows}</tbody>
            </table>
        );
    }
});

var UsersForm = React.createClass({
    render: function() {
        var button_delete, button_cancel, passwords_fields;
        if (this.props.user.id) {
            // button_delete = <button className="btn btn-danger" onClick={this.props.handleDeleteClick}>Dar de baja</button>;
            button_cancel = <button className="btn btn-primary" onClick={this.props.handleCancelClick}>Cancelar</button>;
        } else {
            passwords_fields = <div className="form-group">
                <label forHtml='password'>Contraseña</label>
                <input ref='password' name='password' type='password' value={this.props.user.password} onChange={this.onChange} className="form-control" autoComplete="new-password" />
            </div>;
        }

        return(
            <form onSubmit={this.props.handleSubmitClick}>
                <div className="form-group">
                    <label forHtml='email'>E-mail</label>
                    <input ref='email' name='email' type='text' value={this.props.user.email} onChange={this.onChange} className="form-control" />
                </div>

                <div className="form-group">
                    <label forHtml='name'>Nombres</label>
                    <input ref='name' name='name' type='text' value={this.props.user.name} onChange={this.onChange} className="form-control" />
                </div>

                <div className="form-group">
                    <label forHtml='role_id'>Rol</label>
                    <select ref='role_id' name='role_id' value={this.props.user.role?this.props.user.role.id:''} onChange={this.onChange} className="form-control">
                        <option value='1'>Employee</option>
                        <option value='2'>Administrator</option>
                    </select>
                </div>

                {passwords_fields}

                <button type="submit" className="btn btn-success">{this.props.user.id?"Guardar cambios":"Registrar usuario"}</button>
                {button_delete} {button_cancel}

                {this.props.message?<div className="alert alert-info">{this.props.message}</div>:null}
            </form>
        );
    },
    onChange: function() {
        var email = this.refs.email.value;
        var name = this.refs.name.value;
        var role = {};
        role.id = this.refs.role_id.value;
        role.name = (role.id==1?'Employee':'Administrator');
        var password = this.refs.password.value;
        this.props.handleChange(email, name, role, password);
    }
});

var UsersPanel = React.createClass({
    getInitialState: function() {
        return {
            users: [],
            editingUser: {
                email:"",
                name:"",
                role: {
                    id: ""
                },
                password: ''
            },
            search:"",
            message:""
        };
    },
    render: function() {
        return(
            <div className="row">
                <div className="col-md-8">
                    <SearchPanel search={this.state.search}
                                 onSearchChanged={this.onSearchChanged}
                                 onClearSearch={this.onClearSearch} />
                    <UsersTable users={this.state.users} handleEditClickPanel={this.handleEditClickPanel} handleDeleteClickPanel={this.handleDeleteClickPanel} />
                    <UsersPagination
                        current_page={this.state.current_page}
                        last_page={this.state.last_page}
                        handlePageChange={this.handlePageChange} />
                </div>
                <div className="col-md-4">
                    <UsersForm
                        user={this.state.editingUser}
                        message={this.state.message}
                        handleChange={this.handleChange}
                        handleSubmitClick={this.handleSubmitClick}
                        handleCancelClick={this.handleCancelClick}
                        handleDeleteClick={this.handleDeleteClick}
                    />
                </div>
            </div>
        );
    },
    componentDidMount: function() {
        this.reloadUsers('');
    },

    // Non-ajax object methods
    onSearchChanged: function(query) {
        if (this.promise) {
            clearInterval(this.promise)
        }
        this.setState({
            search: query
        });
        this.promise = setTimeout(function () {
            this.reloadUsers(query);
        }.bind(this), 200);
    },
    onClearSearch: function() {
        this.setState({
            search: ''
        });
        this.reloadUsers('');
    },
    handleEditClickPanel: function(id) {
        var user = $.extend({}, this.state.users.filter(function(x) {
            return x.id == id;
        })[0] );

        this.setState({
            editingUser: user,
            message: ''
        });
    },
    handleChange: function(email, name, role, password) {
        this.setState({
            editingUser: {
                email: email,
                name: name,
                role: role,
                password: password,
                id: this.state.editingUser.id
            }
        });
    },
    handleCancelClick: function(e) {
        e.preventDefault();
        this.setState({
            editingUser: {}
        });
    },

    // The most OP function XD
    handlePageChange: function(page) {
        this.reloadUsers(this.state.search, page);
    },

    // Ajax object methods
    reloadUsers: function(query, page) {
        var url_request = this.props.url+'?search='+query;
        if (page) {
            url_request += '&page='+page;
        }

        $.ajax({
            url: url_request,
            dataType: 'json',
            cache: false,
            success: function(data) {
                this.setState({
                    users: data.data,
                    current_page: data.current_page,
                    last_page: data.last_page
                });
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(this.props.url, status, err.toString());
                this.setState({
                    message: err.toString()
                });
            }.bind(this)
        });
    },
    handleSubmitClick: function(e) {
        e.preventDefault();
        if(this.state.editingUser.id) {
            $.ajax({
                url: this.props.url+'/'+this.state.editingUser.id,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                dataType: 'json',
                method: 'PUT',
                data: this.state.editingUser,
                cache: false,
                success: function(data) {
                    this.setState({
                        message: "Successfully updated user!"
                    });
                    this.reloadUsers('');
                }.bind(this),
                error: function(xhr, status, err) {
                    console.error(this.props.url, status, err.toString());
                    this.setState({
                        message: err.toString()
                    });
                }.bind(this)
            });
        } else {
            $.ajax({
                url: this.props.url,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                method: 'POST',
                data: this.state.editingUser,
                cache: false,
                success: function(data) {
                    this.setState({
                        message: "Successfully added user!"
                    });
                    this.reloadUsers('');
                }.bind(this),
                error: function(xhr, status, err) {
                    console.error(this.props.url, status, err.toString());
                    this.setState({
                        message: err.toString()
                    });
                }.bind(this)
            });
        }
        this.setState({
            editingUser: {}
        });
    },
    handleDeleteClick: function(e) {
        e.preventDefault();
        $.ajax({
            url: this.props.url+this.state.editingUser.id,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            method: 'DELETE',
            cache: false,
            success: function(data) {
                this.setState({
                    message: "Successfully deleted user!",
                    editingBook: {}
                });
                this.reloadUsers('');
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(this.props.url, status, err.toString());
                this.setState({
                    message: err.toString()
                });
            }.bind(this)
        });
    },
});

ReactDOM.render(
    <UsersPanel url={location.href} />,
    document.getElementById('container')
);
