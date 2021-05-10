import ReportList from './Reports/ReportList';
import ReportAdd from './Reports/ReportAdd';
import Example from './components/ExampleComponent'

const routes = [
    {
        path: '/',
        name: 'home',
        component: Example
    },
    {
        path: '/reports',
        name: 'reports list',
        component: ReportList
    },
    {
        path: '/report',
        name: 'report add',
        component: ReportAdd
    }
];

export default routes