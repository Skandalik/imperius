import React from 'react';
import { Link } from 'react-router-dom';
import { Menu, Dropdown } from 'semantic-ui-react';
import Segment from 'semantic-ui-react/dist/commonjs/elements/Segment/Segment';

class Header extends React.Component {
  state = {  }
  handleClickItem = (e, { name }) => this.setState({ activeItem: name })

  render() {
    const { activeItem } = this.state;
    return (
        <Menu fixed='top' size='large'>
          <Menu.Item header> Imperius Project</Menu.Item>
          <Menu.Menu position='right'>
          <Menu.Item as={Link} to='/overview' name='overview' active={activeItem === 'overview'} onClick={this.handleClickItem}></Menu.Item>
            <Dropdown item text='Language'>
              <Dropdown.Menu>
                <Dropdown.Item>English</Dropdown.Item>
                <Dropdown.Item>Russian</Dropdown.Item>
                <Dropdown.Item>Spanish</Dropdown.Item>
              </Dropdown.Menu>
            </Dropdown>
            <Menu.Item as={Link} to='/sensor' name='sensor' active={activeItem === 'sensor'} onClick={this.handleClickItem}></Menu.Item>
            <Menu.Item as={Link} to='/room' name='room' active={activeItem === 'room'} onClick={this.handleClickItem} />
          </Menu.Menu>
        </Menu>
    )
  }
}

export default Header;
