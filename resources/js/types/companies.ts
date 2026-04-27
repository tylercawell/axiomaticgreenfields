export interface Company {

    id: number
    name: string
    branches_count: number
    created_at: string

}

export interface CompaniesIndexProps{

    [key: string]: unknown

    companies: Company[]

    can: {
        view: boolean
        manage: boolean
    }

}